<?php
mb_internal_encoding('UTF-8');
$pageTitle='Добавяне на разходи';
include './includes/header.php';
?>

<a href="index.php">Виж всички разходи</a>

<form method="POST">
    <div>Име на разход: <input type="text" name="expense" placeholder="Име на разход" value="<?php if (isset($_POST['expense'])) echo $_POST['expense']; ?>"/></div>
    <div>Сума: <input type="text" name="amount"placeholder="Сума" value="<?php if (isset($_POST['amount'])) echo $_POST['amount']; ?>"/></div>
    <div>Дата: <input type="text" name="date" placeholder="<?php echo date("d.m.Y"); ?>" value="<?php if (isset($_POST['date'])) echo $_POST['date']; ?>"/></div>
    <div>
        <select name="group">
            <?php
            foreach ($groups as $key=>$value){
                echo '<option value="'.$key.'">'.$value.'</option>';
            }
            ?>
        </select>
    </div>
    <div>
        <input type="submit" value="Добави"/>
    </div>
</form>

<?php
if($_POST){
    $amount=  str_replace(',', '.', $_POST['amount']);
    //Тук стойността на $amount, съдържаща ',' се подменя с '.' - така е възможно въвеждането на сума и със запетайка, и със точка.
    
    $expense=  makeSafe($_POST['expense']);
    $amount= (float)makeSafe($amount);
    //изпълнява се функцията makeSafe, освен това $amount преминава в тип 'float'.
    
    $date= makeSafe($_POST['date']);
    $group= (int)makeSafe($_POST['group']);
    $date= makeSafe($_POST['date']);
    $error= false;
    $date=  strtotime("$date 00:00:01");
    
    if (!$date){		 
        echo '<p>Невалидна дата! Датата трябва да бъде във формат "дд.мм.гггг"!</p>';	
	$error= true;
    } 
    else $date = date('d M Y', $date);
    
    if(mb_strlen($expense)<4){
        echo '<p>Името на разхода е прекалено късо!</p>';
        $error= true;
    }

    if($amount<0.01 || $amount>1000000){
        echo '<p>Въведете сума между 0.01 и 1000000!</p>';
        $error= true;
    }
    
    if(!array_key_exists($group, $groups)){
        echo '<p>Невалидна група!</p>';
        $error= true;
    }
    
    if(!$error){
        $result=$expense.'!'.$amount.'!'.$date.'!'.$group."\n";
        if(file_put_contents('data.txt', $result, FILE_APPEND))
        {
            echo 'Записът е успешен!';
        }
    }
    
}

include './includes/footer.php';
?>
