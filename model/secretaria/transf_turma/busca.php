<?php
include("../../../base/conexao.php"); // Incluir aquivo de conex�o
//mysqli_set_charset($conexao, 'utf-8');

$quantidade = 10;
$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
$inicio = ($quantidade * $pagina) - $quantidade;

$valor = $_GET['valor']; // Recebe o valor enviado
$sql = mysqli_query($conexao, "select * from aluno where concat(nome_alu, sobrenome_alu) like '%$valor%' order by nome_alu asc limit $inicio, $quantidade"); // Procura titulos no banco relacionados ao valor
$numero = mysqli_num_rows($sql);
if($numero == 0){
    echo"
        <tr>
            <td class='table-danger' colspan='8'>Nenhum aluno encontrado!</td>
        </tr>    
    ";
}
 
while ($info = mysqli_fetch_array($sql)) { // Exibe todos os valores encontrados
	echo "<tr scope='row'>";
    echo "<td>".$info['matricula_alu']."</td>";
    echo "<td>".$info['nome_alu']."</td>";
    echo "<td>".$info['sobrenome_alu']."</td>"; 
    echo "<td>".$info['cpf_alu']."</td>";
    echo "<td>".implode("/", array_reverse(explode("-", $info['dt_nasc_alu'])))."</td>";
    switch($info['tipo_alu']){
        case "I";
            echo "<td>Ensino Integrado</td>";
            break;
        case "S";
            echo "<td>Ensino Subsequente</td>";
            break;
    }
    echo "<td>".$info['cep']."</td>";
    echo "<td><div class='btn-group btn-group-sm' role='group'>
                                                            <a class='btn btn-success' href=view/visualizar_alu.php?matricula_alu=".$info['matricula_alu']."><i class='fa fa-info-circle'></i>&nbsp; Detalhes</a>
                                                                
                                                            <a class='btn btn-warning' href=view/editar_alu.php?matricula_alu=".$info['matricula_alu']."><i class='fa fa-edit'></i>&nbsp; Editar</a>
                                                                
                                                            <a class='btn btn-danger' onclick='deletaAlu(".$info['matricula_alu'].")' data-toggle='modal' href='#delete-modal'><i class='fa fa-trash'></i>&nbsp; Excluir</a>
                                                          </div>
                                                        </td></tr>";
}
?>