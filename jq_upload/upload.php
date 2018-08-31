<?php  
if(isset($_FILES["myfile"])){  
    move_uploaded_file($_FILES["myfile"]["tmp_name"],"./".$_FILES["myfile"]["name"]);
    echo ".$_FILES["myfile"]["name"];
}else{
    echo 'no file';
}
?> 
