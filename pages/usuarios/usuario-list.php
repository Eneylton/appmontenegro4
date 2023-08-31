<?php
require __DIR__ . '../../../vendor/autoload.php';

use App\Db\Pagination;
use App\Entidy\Acesso;
use App\Entidy\Cargo;
use App\Entidy\Cliente;
use App\Entidy\Usuario;
use App\Session\Login;

define('TITLE', 'Lista de Usuários');
define('BRAND', 'Usuários');


Login::requireLogin();


$buscar = filter_input(INPUT_GET, 'buscar', FILTER_UNSAFE_RAW);

$condicoes = [
    strlen($buscar) ? 'nome LIKE "%' . str_replace(' ', '%', $buscar) . '%" or 
                       email LIKE "%' . str_replace(' ', '%', $buscar) . '%"' : null
];

$condicoes = array_filter($condicoes);

$where = implode(' AND ', $condicoes);

$qtd = Usuario::getQtd($where);

$pagination = new Pagination($qtd, $_GET['pagina'] ?? 1, 5);

$listar = Usuario::getList('*', 'usuarios', $where, null, 'id desc', $pagination->getLimit());

$cargos = Cargo::getList('*', 'cargos');

$acessos = Acesso::getList('*', 'acessos');

$clientes = Cliente::getList('*', 'clientes');


include __DIR__ . '../../../includes/layout/header.php';
include __DIR__ . '../../../includes/layout/top.php';
include __DIR__ . '../../../includes/layout/menu.php';
include __DIR__ . '../../../includes/layout/content.php';
include __DIR__ . '../../../includes/usuario/usuario-form-list.php';
include __DIR__ . '../../../includes/layout/footer.php';

?>

<script>
    async function EditarUser(id) {
        const dadosResp = await fetch('usuario-modal.php?id=' + id);
        const result = await dadosResp.json();

        const editModal = new bootstrap.Modal(document.getElementById("editModal"));
        editModal.show();
        document.querySelector(".edit-modal").innerHTML = result['dados'];

    }
</script>


<script>
    $(document).ready(function() {
        $('.editbtn').on('click', function() {
            $('#editmodal').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function() {
                return $(this).text();
            }).get();

            $('#id').val(data[0]);
            $('#nome').val(data[1]);
            $('#email').val(data[2]);
            $('#cargos_id').val(data[3]);
            $('#acessos_id').val(data[4]);
        });
    });
</script>