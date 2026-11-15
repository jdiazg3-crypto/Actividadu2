<?php

declare(strict_types=1);

// Guardia de seguridad: bloquear acceso directo a archivos/carpetas 
// El .htaccess redirige internamente cualquier URL que no sea public/ hacia aquí.
// Detectamos ese caso comparando la URL solicitada con la ruta pública esperada.

(function (): void{
    $requestPath = rtrim(
        (string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH),
        '/'
    );
    // dirname de /crud-usuarios/public/index.php  →  /crud-usuarios/public
    $publicBase = rtrim(dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '/index.php')), '/');

    // Si la URL pedida no comienza con /…/public/, fue un acceso directo indebido.
    if ($requestPath !== $publicBase && !str_starts_with($requestPath, $publicBase . '/')) {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $dest = isset($_SESSION['auth']['id']) ? 'home' : 'auth.login';
        header('Location: ' . $publicBase . '/index.php?route=' . $dest);
        exit;
    }
})();

require_once __DIR__ . '/../Common/ClassLoader.php';
require_once __DIR__ . '/../Common/DependencyInjection.php';
require_once __DIR__ . '/../Infrastructure/Entrypoints/Web/Presentation/View.php';
require_once __DIR__ . '/../Infrastructure/Entrypoints/Web/Presentation/Flash.php';

DependencyInjection::boot();
Flash::start();

// ──────────────────────────────────────────────────────────────
// Auth helpers
// ──────────────────────────────────────────────────────────────

function isLoggedIn(): bool
{
    return isset($_SESSION['auth']['id']);
}

function requireAuth(): void
{
    if (!isLoggedIn()) {
        Flash::setMessage('Debes iniciar sesión para acceder a esta sección.');
        View::redirect('auth.login');
    }
}

function getLoggedUser(): array
{
    return is_array($_SESSION['auth'] ?? null) ? $_SESSION['auth'] : array();
}

// ──────────────────────────────────────────────────────────────
// Routing
// ──────────────────────────────────────────────────────────────

$route = isset($_GET['route']) ? trim((string) $_GET['route']) : 'home';
$routes = WebRoutes::routes();

if (!isset($routes[$route])) {
    http_response_code(404);
    View::render('home', buildHomeViewData('Ruta no encontrada.'));
    exit;
}

$definition = $routes[$route];
$httpMethod = strtoupper((string) $_SERVER['REQUEST_METHOD']);

if ($httpMethod !== $definition['method']) {
    http_response_code(405);
    View::render('home', buildHomeViewData('Método HTTP no permitido.'));
    exit;
}

// Protect all user management routes behind authentication.
$publicActions = array('home', 'login', 'authenticate', 'logout', 'forgot', 'forgot.send', 'create', 'store');
if (!in_array($definition['action'], $publicActions, true) && !isLoggedIn()) {
    Flash::setMessage('Debes iniciar sesión para acceder a esta sección.');
    View::redirect('auth.login');
}

try {
    switch ($definition['action']) {

        case 'home':
            View::render('home', buildHomeViewData());
            break;

        case 'create':
            View::render('users/create', buildCreateUserViewData());
            break;

        case 'store':
            $controller = DependencyInjection::getUserController();
            $form = getCreateUserFormData();
            $form['id'] = generateUuid4();
            $errors = validateCreateUserForm($form);

            if (!empty($errors)) {
                Flash::setOld($form);
                Flash::setErrors($errors);
                Flash::setMessage('Corrige los errores del formulario.');
                View::redirect('users.create');
            }

            $request = new CreateUserWebRequest(
                $form['id'],
                $form['name'],
                $form['email'],
                $form['password'],
                $form['role']
            );

            $controller->store($request);

            Flash::setSuccess('Usuario registrado correctamente.');
            View::redirect('users.index');
            break;

        case 'index':
            $controller = DependencyInjection::getUserController();
            $users = $controller->index();
            View::render('users/list', buildListUsersViewData($users));
            break;

        case 'show':
            $controller = DependencyInjection::getUserController();
            $id = isset($_GET['id']) ? trim((string) $_GET['id']) : '';
            $user = $controller->show($id);
            View::render('users/show', array(
                'pageTitle' => 'Detalle de usuario',
                'user' => $user,
                'message' => Flash::message(),
            ));
            break;

        case 'edit':
            $controller = DependencyInjection::getUserController();
            $id = isset($_GET['id']) ? trim((string) $_GET['id']) : '';
            $user = $controller->show($id);
            View::render('users/edit', buildEditUserViewData($user));
            break;

        case 'update':
            $controller = DependencyInjection::getUserController();
            $form = getUpdateUserFormData();
            $errors = validateUpdateUserForm($form);

            if (!empty($errors)) {
                Flash::setOld($form);
                Flash::setErrors($errors);
                Flash::setMessage('Corrige los errores del formulario.');
                header('Location: ?route=users.edit&id=' . urlencode($form['id']));
                exit;
            }

            $request = new UpdateUserWebRequest(
                $form['id'],
                $form['name'],
                $form['email'],
                $form['password'],
                $form['role'],
                $form['status']
            );

            $controller->update($request);

            Flash::setSuccess('Usuario actualizado correctamente.');
            View::redirect('users.index');
            break;

        case 'delete':
            $controller = DependencyInjection::getUserController();
            $id = isset($_POST['id']) ? trim((string) $_POST['id']) : '';
            $controller->delete($id);
            Flash::setSuccess('Usuario eliminado correctamente.');
            View::redirect('users.index');
            break;

                    case 'equipos.index':
                    case 'equipos.search':
                        $search = trim(
                            is_string($_GET['search'] ?? null) ? $_GET['search'] : ''
                        );

                        $equipos = DependencyInjection::getEquipoFutbolController()
                            ->index($search);

                        View::render(
                            $route === 'equipos.search'
                                ? 'equipos/buscar'
                                : 'equipos/listar',
                            buildEquipoViewData(
                                'Equipos de fútbol',
                                array('equipos' => $equipos, 'search' => $search)
                            )
                        );
                        break;

                    case 'equipos.create':
                        View::render(
                            'equipos/agregar',
                            buildEquipoViewData('Agregar equipo')
                        );
                        break;

                    case 'equipos.show':
                    case 'equipos.edit':
                    case 'equipos.confirmDelete':
                        $id = is_string($_GET['id'] ?? null)
                            ? trim($_GET['id'])
                            : '';

                        $equipo = DependencyInjection::getEquipoFutbolController()
                            ->show($id);

                        $templates = array(
                            'equipos.show' => 'detalle',
                            'equipos.edit' => 'editar',
                            'equipos.confirmDelete' => 'eliminar',
                        );

                        View::render(
                            'equipos/' . $templates[$route],
                            buildEquipoViewData(
                                'Equipo: ' . $equipo->getNombre(),
                                array('equipo' => $equipo)
                            )
                        );
                        break;

                    case 'equipos.store':
                    case 'equipos.update':
                        validateEquipoCsrf();

                        $form = getEquipoFormData();
                        $errors = validateEquipoForm($form);

                        if (!empty($errors)) {
                            Flash::setOld($form);
                            Flash::setErrors($errors);
                            Flash::setMessage('Corrige los errores del formulario.');

                            redirectEquipoForm(
                                $route === 'equipos.update',
                                $form['id']
                            );
                        }

                        $controller = DependencyInjection::getEquipoFutbolController();

                        if ($route === 'equipos.store') {
                            $form['id'] = generateUuid4();

                            $controller->store(
                                new CreateEquipoFutbolWebRequest(...array_values($form))
                            );
                        } else {
                            $controller->update(
                                new UpdateEquipoFutbolWebRequest(...array_values($form))
                            );
                        }

                        Flash::setSuccess(
                            $route === 'equipos.store'
                                ? 'Equipo registrado correctamente.'
                                : 'Equipo actualizado correctamente.'
                        );

                        View::redirect('equipos.index');
                        break;

                    case 'equipos.delete':
                        validateEquipoCsrf();

                        $id = is_string($_POST['id'] ?? null)
                            ? trim($_POST['id'])
                            : '';

                        DependencyInjection::getEquipoFutbolController()->delete($id);

                        Flash::setSuccess('Equipo eliminado correctamente.');
                        View::redirect('equipos.index');
                        break;

        case 'login':
            if (isLoggedIn()) {
                View::redirect('home');
            }
            View::render('auth/login', array(
                'pageTitle' => 'Iniciar sesión',
                'message' => Flash::message(),
                'errors' => Flash::errors(),
                'old' => Flash::old(),
            ));
            break;

        case 'authenticate':
            $email = trim(strtolower((string) ($_POST['email'] ?? '')));
            $password = (string) ($_POST['password'] ?? '');
            $authErrors = array();

            if ($email === '') {
                $authErrors['email'] = 'El correo es obligatorio.';
            }
            if ($password === '') {
                $authErrors['password'] = 'La contraseña es obligatoria.';
            }

            if (!empty($authErrors)) {
                Flash::setErrors($authErrors);
                Flash::setOld(array('email' => $email));
                View::redirect('auth.login');
            }

            $loginUseCase = DependencyInjection::getLoginUseCase();
            $command = new LoginCommand($email, $password);
            $user = $loginUseCase->execute($command);

            $_SESSION['auth'] = array(
                'id' => $user->id()->value(),
                'name' => $user->name()->value(),
                'email' => $user->email()->value(),
                'role' => $user->role(),
            );

            Flash::setSuccess('Bienvenido/a, ' . $user->name()->value() . '.');
            View::redirect('home');
            break;

        case 'logout':
            session_destroy();
            header('Location: ?route=auth.login');
            exit;


        case 'forgot':
            View::render('auth/forgot-password', array(
                'pageTitle' => 'Recuperar contraseña',
                'message' => Flash::message(),
                'success' => Flash::success(),
                'errors' => Flash::errors(),
                'old' => Flash::old(),
            ));
            break;

        case 'forgot.send':
            $forgotEmail = trim(strtolower((string) ($_POST['email'] ?? '')));

            if ($forgotEmail === '' || !filter_var($forgotEmail, FILTER_VALIDATE_EMAIL)) {
                Flash::setErrors(array('email' => 'Introduce un correo electrónico válido.'));
                Flash::setOld(array('email' => $forgotEmail));
                View::redirect('auth.forgot');
            }

            $repository = DependencyInjection::getUserRepository();
            $foundUser = $repository->getByEmail(new UserEmail($forgotEmail));

            // Always show generic success to avoid user enumeration.
            if ($foundUser !== null && $foundUser->status() === UserStatusEnum::ACTIVE) {
                // Generate a secure temporary password (10 hex chars ≥ 8 chars).
                $tempPassword = bin2hex(random_bytes(5));
                $newPassword = UserPassword::fromPlainText($tempPassword);
                $updatedUser = $foundUser->changePassword($newPassword);
                $repository->update($updatedUser);

                sendPasswordRecoveryEmail(
                    $foundUser->email()->value(),
                    $foundUser->name()->value(),
                    $tempPassword
                );
            }

            Flash::setSuccess(
                'Si el correo está registrado y la cuenta está activa, ' .
                'recibirás un mensaje con tu contraseña temporal.'
            );
            View::redirect('auth.forgot');
            break;

        default:
            throw new RuntimeException('Acción no soportada.');
    }
} catch (Throwable $exception) {
    $msg = $exception->getMessage();
    Flash::setMessage($msg);

    switch ($route) {

            case 'equipos.store':
            case 'equipos.update':
                Flash::setOld(getEquipoFormData());

                redirectEquipoForm(
                    $route === 'equipos.update',
                    getEquipoFormData()['id']
                );
                break;

            case 'equipos.index':
            case 'equipos.search':
                http_response_code(500);

                View::render(
                    'equipos/listar',
                    buildEquipoViewData(
                        'Equipos de fútbol',
                        array(
                            'equipos' => array(),
                            'loadFailed' => true,
                        )
                    )
                );
                break;

            case 'equipos.show':
            case 'equipos.edit':
            case 'equipos.confirmDelete':
            case 'equipos.delete':
                View::redirect('equipos.index');
                break;
        case 'users.store':
            Flash::setOld(getCreateUserFormData());
            View::redirect('users.create');
            break;

        case 'users.update':
            $updateId = trim((string) ($_POST['id'] ?? ''));
            Flash::setOld(getUpdateUserFormData());
            header('Location: ?route=users.edit&id=' . urlencode($updateId));
            exit;

        case 'auth.authenticate':
            Flash::setOld(array('email' => trim(strtolower((string) ($_POST['email'] ?? '')))));
            View::redirect('auth.login');
            break;

        case 'auth.forgot.send':
            Flash::setOld(array('email' => trim((string) ($_POST['email'] ?? ''))));
            View::redirect('auth.forgot');
            break;

        case 'users.show':
        case 'users.edit':
            View::redirect('users.index');
            break;

        case 'users.delete':
            Flash::setMessage($msg);
            View::redirect('users.index');
            break;

        default:
            View::render('home', buildHomeViewData($msg));
            break;
    }
}


function sendPasswordRecoveryEmail(string $email, string $name, string $tempPassword): void
{
    $templateFile = __DIR__ . '/../Infrastructure/Entrypoints/Web/Presentation/Views/emails/forgot-password.php';

    ob_start();
    extract(array('email' => $email, 'name' => $name, 'tempPassword' => $tempPassword), EXTR_SKIP);
    require $templateFile;
    $htmlBody = (string) ob_get_clean();

    ClassLoader::loadClass('EmailDestinationModel');

    $destination = new EmailDestinationModel(
        $email,
        $name,
        'Recuperación de contraseña',
        $htmlBody
    );

    DependencyInjection::getEmailSenderService()->send($destination);
}


/**
 * @param UserResponse[] $users
 * @return array<string, mixed>
 */
function buildListUsersViewData(array $users): array
{
    return array(
        'pageTitle' => 'Lista de usuarios',
        'users' => $users,
        'message' => Flash::message(),
        'success' => Flash::success(),
    );
}

/**
 * @return array<string, mixed>
 */
function buildHomeViewData(string $message = ''): array
{
    return array(
        'pageTitle' => 'Menú principal',
        'message' => $message,
        'success' => Flash::success(),
    );
}

/**
 * @return array<string, mixed>
 */
function buildCreateUserViewData(): array
{
    return array(
        'pageTitle' => 'Registrar usuario',
        'roleOptions' => UserRoleEnum::values(),
        'message' => Flash::message(),
        'success' => Flash::success(),
        'errors' => Flash::errors(),
        'old' => Flash::old(),
    );
}

/**
 * @return array<string, mixed>
 */
function buildEditUserViewData(UserResponse $user): array
{
    return array(
        'pageTitle' => 'Editar usuario',
        'user' => $user,
        'roleOptions' => UserRoleEnum::values(),
        'statusOptions' => UserStatusEnum::values(),
        'message' => Flash::message(),
        'errors' => Flash::errors(),
        'old' => Flash::old(),
    );
}


function generateUuid4(): string
{
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}


/**
 * @return array<string, string>
 */
function getCreateUserFormData(): array
{
    return array(
        'name' => isset($_POST['name']) ? trim((string) $_POST['name']) : '',
        'email' => isset($_POST['email']) ? trim((string) $_POST['email']) : '',
        'password' => isset($_POST['password']) ? trim((string) $_POST['password']) : '',
        'role' => isset($_POST['role']) ? trim((string) $_POST['role']) : '',
    );
}

/**
 * @return array<string, string>
 */
function getUpdateUserFormData(): array
{
    return array(
        'id' => isset($_POST['id']) ? trim((string) $_POST['id']) : '',
        'name' => isset($_POST['name']) ? trim((string) $_POST['name']) : '',
        'email' => isset($_POST['email']) ? trim((string) $_POST['email']) : '',
        'password' => isset($_POST['password']) ? (string) $_POST['password'] : '',
        'role' => isset($_POST['role']) ? trim((string) $_POST['role']) : '',
        'status' => isset($_POST['status']) ? trim((string) $_POST['status']) : '',
    );
}


/**
 * @param array<string, string> $form
 * @return array<string, string>
 */
function validateCreateUserForm(array $form): array
{
    $errors = array();

    if ($form['name'] === '') {
        $errors['name'] = 'El nombre es obligatorio.';
    }

    if ($form['email'] === '') {
        $errors['email'] = 'El correo es obligatorio.';
    } elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'El correo no tiene un formato válido.';
    }

    if ($form['password'] === '') {
        $errors['password'] = 'La contraseña es obligatoria.';
    } elseif (strlen($form['password']) < 8) {
        $errors['password'] = 'La contraseña debe tener al menos 8 caracteres.';
    }

    if ($form['role'] === '') {
        $errors['role'] = 'El rol es obligatorio.';
    }

    return $errors;
}

/**
 * @param array<string, string> $form
 * @return array<string, string>
 */
function validateUpdateUserForm(array $form): array
{
    $errors = array();

    if ($form['name'] === '') {
        $errors['name'] = 'El nombre es obligatorio.';
    }

    if ($form['email'] === '') {
        $errors['email'] = 'El correo es obligatorio.';
    } elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'El correo no tiene un formato válido.';
    }

    // Password is optional on update; validate only if provided.
    if ($form['password'] !== '' && strlen($form['password']) < 8) {
        $errors['password'] = 'La contraseña debe tener al menos 8 caracteres si deseas cambiarla.';
    }

    if ($form['role'] === '') {
        $errors['role'] = 'El rol es obligatorio.';
    }

    if ($form['status'] === '') {
        $errors['status'] = 'El estado es obligatorio.';
    }

        return $errors;
    }

    function equipoFields(): array
    {
        return array(
            'nombre' => array(
                'label' => 'Nombre',
                'type' => 'text',
                'max' => 100,
                'required' => true,
            ),
            'eslogan' => array(
                'label' => 'Eslogan',
                'type' => 'text',
                'max' => 150,
                'required' => false,
            ),
            'tecnico' => array(
                'label' => 'Técnico',
                'type' => 'text',
                'max' => 100,
                'required' => true,
            ),
            'pais' => array(
                'label' => 'País',
                'type' => 'text',
                'max' => 60,
                'required' => true,
            ),
            'ciudad' => array(
                'label' => 'Ciudad',
                'type' => 'text',
                'max' => 60,
                'required' => true,
            ),
            'categoria' => array(
                'label' => 'Categoría',
                'type' => 'text',
                'max' => 50,
                'required' => true,
            ),
            'numGoles' => array(
                'label' => 'Goles',
                'type' => 'number',
            ),
            'numPartidosJugados' => array(
                'label' => 'Partidos jugados',
                'type' => 'number',
            ),
            'numPartidosGanados' => array(
                'label' => 'Partidos ganados',
                'type' => 'number',
            ),
            'numEmpates' => array(
                'label' => 'Empates',
                'type' => 'number',
            ),
            'numCampeonatos' => array(
                'label' => 'Campeonatos',
                'type' => 'number',
            ),
            'numExpulsiones' => array(
                'label' => 'Expulsiones',
                'type' => 'number',
            ),
        );
    }

    function getEquipoFormData(): array
    {
        $form = array(
            'id' => is_string($_POST['id'] ?? null)
                ? trim($_POST['id'])
                : '',
        );

        foreach (equipoFields() as $key => $definition) {
            $form[$key] = is_string($_POST[$key] ?? null)
                ? trim($_POST[$key])
                : '';
        }

        return $form;
    }

    function validateEquipoForm(array $form): array
    {
        $errors = array();

        foreach (equipoFields() as $key => $definition) {
            $value = $form[$key];

            if ($definition['type'] === 'number') {
                $number = filter_var($value, FILTER_VALIDATE_INT);

                if ($number === false || $number < 0 || $number > 2147483647) {
                    $errors[$key] = 'Introduce un entero entre 0 y 2147483647.';
                }
            } elseif ($definition['required'] && $value === '') {
                $errors[$key] = 'Este campo es obligatorio.';
            } elseif (mb_strlen($value) > $definition['max']) {
                $errors[$key] = 'Máximo ' . $definition['max'] . ' caracteres.';
            }
        }

        if (!isset($errors['nombre']) && mb_strlen($form['nombre']) < 2) {
            $errors['nombre'] = 'El nombre debe tener al menos 2 caracteres.';
        }

        if (
            !isset($errors['numPartidosJugados'])
            && !isset($errors['numPartidosGanados'])
            && !isset($errors['numEmpates'])
            && (int) $form['numPartidosGanados'] + (int) $form['numEmpates']
                > (int) $form['numPartidosJugados']
        ) {
            $errors['numPartidosJugados'] =
                'Los ganados más los empates no pueden superar los partidos jugados.';
        }

        return $errors;
    }

    function buildEquipoViewData(string $title, array $extra = array()): array
    {
        if (!isset($_SESSION['equipos_csrf'])) {
            $_SESSION['equipos_csrf'] = bin2hex(random_bytes(32));
        }

        return array_merge(
            array(
                'pageTitle' => $title,
                'message' => Flash::message(),
                'success' => Flash::success(),
                'errors' => Flash::errors(),
                'old' => Flash::old(),
                'fields' => equipoFields(),
                'csrf' => $_SESSION['equipos_csrf'],
            ),
            $extra
        );
    }

    function validateEquipoCsrf(): void
    {
        $token = $_POST['csrf'] ?? null;

        if (
            !is_string($token)
            || !isset($_SESSION['equipos_csrf'])
            || !hash_equals($_SESSION['equipos_csrf'], $token)
        ) {
            throw new RuntimeException(
                'El formulario expiró. Vuelve a intentarlo.'
            );
        }
    }

    function redirectEquipoForm(bool $editing, string $id): void
    {
        header(
            'Location: ?route='
            . ($editing
                ? 'equipos.edit&id=' . urlencode($id)
                : 'equipos.create')
        );
        exit;
    }

