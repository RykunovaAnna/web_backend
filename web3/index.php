<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
header('Content-Type: text/html; charset=UTF-8');


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!empty($_GET['save'])) {
        print('Спасибо, форма сохранена.');
    }
    include('form.html');
    exit();
}

$errors = FALSE;
if (empty($_POST['name'])) {
    print('Укажите ФИО.<br/>');
    $errors = TRUE;
} else {
    $name = $_POST['name'];
    if (!preg_match('/^[a-zA-Zа-яА-Я\s]{1,150}$/', $name)) {
        print('Неверный формат ФИО. Допустимы только буквы и пробелы, не более 150 символов.<br/>');
        $errors = TRUE;
    }
}

if (empty($_POST['phone']) || !preg_match('/^\+?\d{1,15}$/', $_POST['phone'])) {
    print('Укажите корректный телефонный номер.<br/>');
    $errors = TRUE;
}

if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    print('Укажите корректный адрес электронной почты.<br/>');
    $errors = TRUE;
}

if (empty($_POST['date'])) {
    print('Укажите день рождения.<br/>');
    $errors = TRUE;
}

if (empty($_POST['gender']) ) {
    print('Укажите пол.<br/>');
    $errors = TRUE;
}

switch($_POST['gender']) {
    case 'm': {
        $gender='m';
        break;
    }
    case 'f':{
        $gender='f';
        break;
    }
};

if (empty($_POST['Languages'])) {
    print('Укажите хотя бы один язык программирования.<br/>');
    $errors = TRUE;
}

$selectedLanguages = array(
    'Pascal', 'C', 'Cplusplus', 'JavaScript', 'PHP',
    'Python', 'Java', 'Haskel', 'Clojure', 'Prolog', 'Scala'
);
$languageValues = array();
foreach ($selectedLanguages as $language) {
    $languageValues[] = in_array($language, $_POST['Languages']) ? '1' : '0';
}

if (empty($_POST['biography'])) {
    print('Напишите кратко биографию.<br/>');
    $errors = TRUE;
}

if (empty($_POST['agree'])) {
    print('Вы не согласились с условиями контракта!<br/>');
    $errors = TRUE;
}
$agree = 'agree';

if ($errors) {
    exit();
}

$user = 'u67313';
$pass = '4344635';
$db = new PDO('mysql:host=localhost;dbname=u67313', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

try {
    $stmt = $db->prepare("INSERT INTO application SET name = ?, phone = ?, email = ?, date = ?, gender = ?, 
        Pascal = ?, C = ?, Cplusplus = ?, JavaScript = ?, PHP = ?, Python = ?, Java = ?, Haskel = ?, Clojure = ?, Prolog = ?, Scala = ?,
        biography = ?, agree = ?");
    $stmt -> execute(array($_POST['name'],$_POST['phone'],$_POST['email'],$_POST['date'],$gender,$languageValues,$_POST['biography'], $agree));
}
catch(PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
}

header('Location: ?save=1');
?>