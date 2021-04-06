    <?php

    $erro = null;
    $valido = false;

    if(isset($_REQUEST["validar"]) && $_REQUEST["validar"] == true)
    {
            if(strlen(utf8_decode($_POST["nome"])) < 5)
            {
                $erro = "Preencha o campo nome corretamente (5 ou mais caracteres)";
            }
            else if(strlen(utf8_decode($_POST["email"])) < 6)
            {
                $erro = "E-mail inválido, preencha corretamente";
            }
            else if(is_numeric($_POST["idade"]) == false)
            {
                $erro = "Campo idade deve ser numérico";  
            }
            else if($_POST["sexo"] != "M" && $_POST["sexo"] != "F")
            {
                $erro = "Selecione o campo sexo corretamente";
            }
            else if($_POST["estadocivil"] != "Solteiro(a)" &&
                    $_POST["estadocivil"] != "Casado(a)" &&
                    $_POST["estadocivil"] != "Divorciado(a)" &&
                    $_POST["estadocivil"] != "Viúvo(a)")
            {
                $erro = "Selecione o campo de estado civil corretamente";
            }
            else
            {
                $valido = true;


                try
                {
                    $connection = new PDO('mysql:host=localhost:3306; dbname=curso_php','root','');
                    $connection->exec("set names uft8");
                    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                }

                catch(pdoException $e)
                {
                    echo "falha" . $e->getmessage();
                    exit();
                }

                $sql = "INSERT INTO usuarios (nome, email, idade, sexo, estado_civil, humanas, exatas, biologicas, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = $connection->prepare($sql);
                $stmt->bindParam(1, $POST["nome"]);
                $stmt->bindParam(2, $POST["email"]);
                $stmt->bindParam(1, $POST["idade"]);
                $stmt->bindParam(1, $POST["sexo"]);
                $stmt->bindParam(1, $POST["estado_civil"]);
                
               $checkHumanas = isset($POST["humanas"]) ? 1 : 0;
               $stmt->bindParam(6, $checkHumanas);

               $checkExatas = isset($POST["exatas"]) ? 1 : 0;
               $stmt->bindParam(7, $checkExatas);
               
               $checkBiologicas = isset($POST["biologicas"]) ? 1 : 0;
               $stmt->bindParam(8, $checkBiologicas);
               
               $passwordhash = md5($_POST["senha"]);
               $stmt->bindParam(9, $passwordHash);

               $stmt->execute();

               if($stmt->errorCode() != "0000")
               {
                   $valido = false;
                   $erro = "Erro código" . $stmt->errorCode() . ": ";
                   $erro .= implode(", ", $stmt->errorInfo());
               }

               
            }

        }

    ?>
    <HTML>
        <HEAD>
            <TITLE>Formulários: Avançado</TITLE>
        </HEAD>
        <BODY>
            <?php
            
                if($valido == true)
                {
                    echo "Dados enviados com sucesso!";
                }
                else
                {
            
                    if(isset($erro))
                    {
                        echo $erro . "<BR><BR>";
                    }
            
            ?>
            <FORM method=POST action="?validar=true">
            
                Nome:
                <INPUT type=TEXT name=nome 
                <?php if(isset($_POST["nome"])) { echo "value='" . $_POST["nome"] . "'"; } ?>
                ><BR>
                
                E-mail:
                <INPUT type=TEXT name=email
                <?php if(isset($_POST["email"])) { echo "value='" . $_POST["email"] . "'"; } ?>
                ><BR>
                
                Idade:
                <INPUT type=TEXT name=idade
                <?php if(isset($_POST["idade"])) { echo "value='" . $_POST["idade"] . "'"; } ?>
                ><BR>
                
                Sexo:
                <INPUT type=RADIO name=sexo value="M"
                <?php if(isset($_POST["sexo"]) && $_POST["sexo"] == "M") { echo "checked"; } ?>
                >Masculino
                
                <INPUT type=RADIO name=sexo value="F"
                <?php if(isset($_POST["sexo"]) && $_POST["sexo"] == "F") { echo "checked"; } ?>
                >Feminino
                <BR>

                Interesses:

                <INPUT type=CHECKBOX name="humanas"
                <?php if(isset($_POST["humanas"])) { echo "checked"; } ?>                
                >Ciências Humanas
                
                <INPUT type=CHECKBOX name="exatas"
                <?php if(isset($_POST["exatas"])) { echo "checked"; } ?>
                >Ciências Exatas
                
                <INPUT type=CHECKBOX name="biologicas"
                <?php if(isset($_POST["biologicas"])) { echo "checked"; } ?>
                >Ciências Biológicas:
                <BR>
                    
                Estado civil:
                <SELECT name="estadocivil">
                    <OPTION>Selecione...</OPTION>
                    
                    <OPTION
                    <?php
                    if(isset($_POST["estadocivil"]) && $_POST["estadocivil"] == "Solteiro(a)")
                    { echo "selected"; }
                    ?>
                    >Solteiro(a)</OPTION>
                    
                    <OPTION
                    <?php
                    if(isset($_POST["estadocivil"]) && $_POST["estadocivil"] == "Casado(a)")
                    { echo "selected"; }
                    ?>
                    >Casado(a)</OPTION>
                    
                    <OPTION
                    <?php
                    if(isset($_POST["estadocivil"]) && $_POST["estadocivil"] == "Divorciado(a)")
                    { echo "selected"; }
                    ?>
                    >Divorciado(a)</OPTION>
                    
                    <OPTION
                    <?php
                    if(isset($_POST["estadocivil"]) && $_POST["estadocivil"] == "Viúvo(a)")
                    { echo "selected"; }
                    ?>
                    >Viúvo(a)</OPTION>
                </SELECT>
                <BR>
                    
                Senha:
                <INPUT type=PASSWORD name="senha"><BR>
                <INPUT type=SUBMIT value="Enviar">

            </FORM>
            <?php
                }
            ?>
        </BODY>
    </HTML>