<?php
mb_internal_encoding('UTF-8');
$pageTitle='Разходи';
include './includes/header.php';
?>  

<a href="form.php">Добави нов разход</a>
 
<form method="POST">
    <div>Филтър по вид:
        <select name="type">			
            <?php 			
            echo '<option value="0" >Всички</option>';									
            foreach ($groups as $key=>$typeValue) {
                if ($_POST['type']==$key){
                    $selected='selected';
                }
                else{
                    $selected='';
                }
                echo '<option value="'.$key.'"'.$selected.'>'.$typeValue.'</option>';
            }
            ?>		
        </select> 
    <input type="submit" value="Филтрирай"/>
    </div>
</form>
 
<table border="10">
    <tr>
        <td>Разход</td>
        <td>Сума</td>
        <td>Дата</td>
        <td>Вид</td>
    </tr>
                
    <?php
    if(file_exists('data.txt')){
        $result= file('data.txt');
        $sum= 0;
        foreach ($result as $value){
            $columns=  explode('!', $value);
            if(!$_POST){
                echo '<tr>';
                echo '<td>'.$columns[0].'</td>';
                echo '<td>'.$columns[1].'</td>';
                echo '<td>'.$columns[2].'</td>';
                echo '<td>'.$groups[(int)$columns[3]].'</td>';        
                echo '</tr>';
                $sum+= $columns[1];
            }
            else{
                $chosenType=  makeSafe($_POST['type']);
                if((int)$columns[3] == $chosenType || $chosenType == '0'){			
                    echo '<tr>';
                    echo '<td>'.$columns[0].'</td>';
                    echo '<td>'.$columns[1].'</td>';
                    echo '<td>'.$columns[2].'</td>';
                    echo '<td>'.$groups[(int)$columns[3]].'</td>';
                    echo '</tr>';
                    $sum+= $columns[1];
                }
            }
        }
        echo '<tr>';
        echo '<td>'.'Обща стойност: '.$sum;
    }
    ?>
</table>

<?php
include './includes/footer.php';
?>                     