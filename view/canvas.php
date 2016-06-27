<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">    
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <style>
            /*.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
            .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
            .autocomplete-selected { background: #F0F0F0; }
            .autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }*/
            //input { font-size: 28px; padding: 10px; border: 1px solid #CCC; margin: 20px 0; }
        </style> 
    </head>
    <body>

<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>-->
<!--<script src="../lib/jquery-1.6.1.min.js"></script>-->
        <form method="post" action="canvas.php" enctype="multipart/form-data">
            <input  value="<?php if (isset($_POST['key'])) echo $_POST['key']; ?>" type="text" name="key" id="keypress" style="width: 600px;" placeholder="keywords..."/>
            <input style="margin-top: -20px" type="checkbox" name="synonym" value="true" <?php if (isset($_REQUEST['synonym'])) echo 'checked' ?>/>Synonym<br/>
            <hr>
            Type de retour :
            <input type="checkbox" name="returntype[]" value="video" c>Vidéo 
            <input type="checkbox" name="returntype[]" value="image" >Image 
            <input type="checkbox" name="returntype[]" value="audio" >Audio 
            <input type="checkbox" name="returntype[]" value="T ext" >Texte 
            <br/>
            <hr>
            Afficher les relations 
            <select name="choixRelationSemantique">
                <option value="non">Non</option>
                <option value="oui">Oui</option>
            </select>
            <input type="checkbox" name="relationsemantique[]" value="speak">Speak
            <input type="checkbox" name="relationsemantique[]" value="speak_about">Speak about
            <input type="checkbox" name="relationsemantique[]" value="talk">Talk
            <input type="checkbox" name="relationsemantique[]" value="talk_about">Talk about
            <input type="checkbox" name="relationsemantique[]" value="show">Show
            <input type="checkbox" name="relationsemantique[]" value="Appear_In">Appear in
            <br/>
            <hr>
            <br/>
            <input type="file" name="searchfile" value="searchfile" accept=".xml"><br/>
            <hr>
            Options de similarité 
            <input type="checkbox" name="similarityoption[]" value="headline">Headline
            <input type="checkbox" name="similarityoption[]" value="keyword">Keyword
            <input type="checkbox" name="similarityoption[]" value="description">Description
            <br/>
            <hr>
            <br/>
            <input type="checkbox" name="xquery_method" id="xquery_method"> xquery search &nbsp;&nbsp;
            <input type="checkbox" name="sparq_method" id="sparq_method"> sparq search <br/>
            <hr>
            <input type="submit" value="chercher">
            <input type="reset" value="annuler">
        </form>
        <script type="text/javascript" src="../lib/jquery.min.js"></script>
        <script type="text/javascript" src="jquery.autocomplete.min.js"></script>
        <script type="text/javascript" src="springy.js"></script>
        <script type="text/javascript" src="springyui.js"></script>        
        <script type="text/javascript">
          $(document).ready(function () {
              $('#keypress').autocomplete({
                  serviceUrl: '../backend/dbpedia/lookup.php',
                  dataType: 'json'
              });
          });
        </script>
        <?php
        if (isset($_POST['xquery_method']) && !isset($_POST['sparq_method']))
          include_once '../backend/xquerywork/traitement.php';
        else if (!isset($_POST['xquery_method']) && isset($_POST['sparq_method'])) {
          if (isset($_POST['key']) && $_POST['key'] != '') {
            include_once '../backend/controller/DbTripletsToJson.php';
            echo          ' <script type="text/javascript">
            jQuery(function () {
                var springy = jQuery(\'#springydemo\').springy({
                });
            });
          </script>
          <canvas id="springydemo" width="1100" height="480" />';
          }
          }
        else {
          include_once '../backend/xquerywork/traitement.php';
          if (isset($_POST['key']) && $_POST['key'] != '') {
            include_once '../backend/controller/DbTripletsToJson.php';
            echo          ' <script type="text/javascript">
            jQuery(function () {
                var springy = jQuery(\'#springydemo\').springy({
                });
            });
          </script>
          <canvas id="springydemo" width="1100" height="480" />';
          }
          else
            echo "<br/> il n'y a pas de mots clés pour executer les requetes sparql <br/>";
        }
        ?>
    </body>
</html>
