<?php

// // for older PHP versions(under php 8) uncomment these functions:
//
// function str_starts_with ( $haystack, $needle ) {
//     return strpos( $haystack , $needle ) === 0;
// } 
// function str_ends_With($haystack, $needle) {
//     $length = strlen($needle);
//     return $length > 0 ? substr($haystack, -$length) === $needle : true;
// }
//

//get the names from json file
$json = json_decode(file_get_contents('people.json'), true);
$en_names_array = array_keys($json);

//if the form filled and posted
if (isset($_POST["question"]) and $_POST['question'] != '') {
    $question = $_POST["question"];
    $en_name = $_POST["person"];
    $fa_name = $json[$en_name];

    //generating an answer
    $code = intval(hash('sha256', $question . $fa_name)) % 16;
    $answers = file('messages.txt');
    $msg = $answers[$code];

    //check the question
    if (!str_starts_with($question, "آیا") or (!str_ends_with($question, "?") and !str_ends_with($question, "؟"))) {
        $msg = 'سوال درستی پرسیده نشده';
    }
} else {    //if nothing posted
    $question = '';
    $en_name = array_rand($json);
    $fa_name = $json[$en_name];
    $msg = 'سوال خود را بپرس!';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>
<body>
    <p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
    <div id="wrapper">
        <div id="title">
            <span id="label"> <?php if ($question != NULL) echo "پرسش:" ?> </span>
            <span id="question"><?php echo $question ?></span>
        </div>
        <div id="container">
            <div id="message">
                <p><?php echo $msg ?></p>
            </div>
            <div id="person">
                <div id="person">
                    <img src="images/people/<?php echo "$en_name.jpg" ?>"/>
                    <p id="person-name"><?php echo $fa_name ?></p>
                </div>
            </div>
        </div>
        <div id="new-q">
            <form method="post" action="index.php">
                سوال
                <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..."/>
                را از
                <select name="person">
                    <?php foreach ($en_names_array as $name) { ?>
                        <option value=<?php echo $name;
                                        if ($name == $en_name) echo ' selected'; ?>> <?php echo $json[$name]; ?></option>
                    <?php } ?>
                </select>
                <input type="submit" value="بپرس"/>
            </form>
        </div>
    </div>
</body>

</html>
