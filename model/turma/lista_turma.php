<?php
if (!isset($_SESSION)) session_start(); // A sessão precisa ser iniciada em cada página diferente
$nivel_necessario = 2;

if (!isset($_SESSION['UsuarioID']) OR ($_SESSION['UsuarioNivel'] < $nivel_necessario)) { // Verifica se não há a variável da sessão que identifica o usuário
	session_destroy(); // Destrói a sessão por segurança
	header("Location: index.php"); exit; // Redireciona o visitante de volta pro login
}
include "../../base/head.php";
?>
</head>

<body class="sidebar-fixed header-fixed">
    <?php include "modal.php" ?>
    <div class="page-wrapper">
    <?php include "../../base/nav.php" ?>
        <div class="main-container">
        <?php include "../../base/sidebar.php" ?>
            <div class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <div class="row">
                                    <div class="col-md-2">
                                        <h3>Turmas</h3>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" id="busca" onkeyup="searchTurma(this.value)" class="form-control">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp; Pesquisar</button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="view/cadastrar_turma.php"><button id='add' type="button" class="btn btn-primary col-sm-12"><i class="fa fa-plus-circle"></i>&nbsp; Adicionar</button></a>
                                    </div>
                                </div>
                            </div>
                            <div id="card-body" class="card-body">
                            <?php include "messages.php"; ?>
                                <div id="table-list" class="table-responsive">
                                    <table id="tabela_turma" class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col">Número</th>
                                                <th scope="col">Ano letivo</th>
                                                <th scope="col">Situação</th>
                                                <th scope="col">Turno</th>
                                                <th scope="col">Curso</th>
                                                <th scope="col">Data de início</th>
                                                <th scope="col">Data de fim</th>
                                                <th scope="col">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody_turma">
                                        <?php
                                            include("../../base/conexao.php");
                                                    
                                            $quantidade = 10;
                                            $pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
                                            $inicio = ($quantidade * $pagina) - $quantidade;

                                            $data = mysqli_query($conexao, "select * from turma order by numero asc limit $inicio, $quantidade;") or die(mysql_error());
                                                
                                            while($info = mysqli_fetch_array($data)){                                   
                                                echo "<tr scope='row'>";
                                                echo "<td>".$info['id_turma']."</td>";
                                                echo "<td>".$info['numero']."</td>";
                                                echo "<td>".$info['ano_letivo']."</td>";
                                                switch($info['situacao']){
                                                    case 1:
                                                        echo "<td>Ativa</td>"; 
                                                        break;
                                                    case 0:
                                                        echo "<td>Encerrada</td>";
                                                        break;
                                                }
                                                switch($info['turno']){
                                                    case 1:
                                                        echo "<td>Manhã</td>"; 
                                                        break;
                                                    case 2:
                                                        echo "<td>Tarde</td>";
                                                        break;
                                                    case 3:
                                                        echo "<td>Noite/td>";
                                                        break;
                                                }
                                                switch($info['id_cur']){
                                                    case 0:
                                                        echo "<td>Administração</td>"; 
                                                        break;
                                                    case 3:
                                                        echo "<td>Análises Clínicas</td>";
                                                        break;
                                                    case 4:
                                                        echo "<td>Gerência em Saúde</td>";
                                                        break;
                                                    case 5:
                                                        echo "<td>Informática para Internet</td>";
                                                        break;
                                                }
                                                echo "<td>".implode("/", array_reverse(explode("-", $info['dt_inicio'])))."</td>";
                                                switch($info['dt_fim']){
                                                    case 0:
                                                        echo "<td>Presente</td>";
                                                        break;
                                                    default:
                                                        echo "<td>".implode("/", array_reverse(explode("-", $info['dt_fim'])))."</td>";
                                                }  
                                                echo "<td><div class='btn-group btn-group-sm' role='group'>
                                                            <a class='btn btn-success' href=view/visualizar_turma.php?id_turma=".$info['id_turma']."><i class='fa fa-info-circle'></i>&nbsp; Detalhes</a>
                                                                                                                        
                                                            <a class='btn btn-warning' href=view/editar_turma.php?id_turma=".$info['id_turma']."><i class='fa fa-edit'></i>&nbsp; Editar</a>
                                                                
                                                            <a class='btn btn-danger' onclick='deletaTurma(".$info['id_turma'].")' data-toggle='modal' href='#delete-modal'><i class='fa fa-trash'></i>&nbsp; Excluir</a>
                                                          </div>
                                                        </td></tr>";
                                            }
                                        ?>
                                        </tbody>
                                    </table>
                                    <nav aria-label="Paginação">
                                        <ul class="pagination">
                                        <?php 
                                            $sqlTotal 		= "select id_turma from turma;";
                                                
                                            $qrTotal  		= mysqli_query($conexao, $sqlTotal) or die (mysql_error());
                                            $numTotal 		= mysqli_num_rows($qrTotal);
                                            $totalpagina = (ceil($numTotal/$quantidade)<=0) ? 1 : ceil($numTotal/$quantidade);

                                            $exibir = 3;
                                            $anterior = (($pagina-1) <= 0) ? 1 : $pagina - 1;
                                            $posterior = (($pagina+1) >= $totalpagina) ? $totalpagina : $pagina+1;

                                            echo "<li class='page-item'><a class='page-link' href='?pagina=1'> Primeira</a></li> "; 
                                            echo "<li class='page-item'><a class='page-link' href=\"?pagina=$anterior\">&laquo;</a></li> ";
                                                
                                            echo '<li class="page-item"><a class="page-link" href="?pagina='.$pagina.'"><strong>'.$pagina.'</strong></a></li> ';

                                            for($i = $pagina+1; $i < $pagina+$exibir; $i++){
                                                if($i <= $totalpagina){
                                                    echo '<li class="page-item"><a class="page-link" href="?pagina='.$i.'"> '.$i.' </a></li> '; 
                                                }    
                                            }

                                            echo "<li class='page-item'><a class='page-link' href=\"?pagina=$posterior\">&raquo;</a></li> ";
                                            echo "<li class='page-item'><a class='page-link' href=\"?pagina=$totalpagina\">Última</a></li>";
                                        ?>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>       
        </div>
    </div>     
    
    <script src="\projeto/assets/js/jquery-3.3.1.min.js"></script>
    <script src="\projeto/assets/js/bootstrap.min.js"></script>
    <script src="search.js"></script>
    <script src="\projeto/assets/js/function-delete.js"></script>
    <script src="\projeto/assets/js/carbon.js"></script>
</body>

</html>
