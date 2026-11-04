<?php
// Көмекші функциялар

/** HTML-ға қауіпсіз шығару (XSS-тен қорғау) */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** POST өрісін қауіпсіз алу */
function post(string $key): string
{
    return trim($_POST[$key] ?? '');
}

/** GET өрісін қауіпсіз алу */
function get(string $key, string $default = ''): string
{
    return trim($_GET[$key] ?? $default);
}

/** Өріске қатысты қате хабарламасы бар ма */
function field_class(array $errors, string $name): string
{
    return isset($errors[$name]) ? 'field invalid' : 'field';
}

/** Өрістің астына қате мәтінін шығару */
function field_error(array $errors, string $name): string
{
    return '<div class="error">' . e($errors[$name] ?? '') . '</div>';
}

/** Тіркелу формасын серверде тексеру */
function validate_register(array $data): array
{
    $errors = [];

    if (mb_strlen($data['full_name']) < 3 || mb_strlen($data['full_name']) > 100) {
        $errors['full_name'] = 'Аты-жөніңізді толық жазыңыз (3-тен 100 таңбаға дейін)';
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email дұрыс емес';
    }
    $phone = preg_replace('/[\s()-]/', '', $data['phone']);
    if (!preg_match('/^\+?\d{10,15}$/', $phone)) {
        $errors['phone'] = 'Телефон нөмірін дұрыс жазыңыз';
    }
    if (strlen($data['password']) < 6) {
        $errors['password'] = 'Пароль кемінде 6 таңба болуы керек';
    }
    if ($data['password2'] !== $data['password']) {
        $errors['password2'] = 'Парольдер сәйкес келмейді';
    }
    return $errors;
}

/** Кіру формасын серверде тексеру */
function validate_login(array $data): array
{
    $errors = [];
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email дұрыс емес';
    }
    if ($data['password'] === '') {
        $errors['password'] = 'Парольді енгізіңіз';
    }
    return $errors;
}
