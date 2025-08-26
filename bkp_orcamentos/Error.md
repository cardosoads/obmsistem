Internal Server Error
Copy as Markdown

ParseError
Unclosed '{' on line 9
POST obmsistem.test
PHP 8.4.11 — Laravel 12.25.0

Expand
vendor frames

App\Models\OrcamentoPrestador
:1
{closure:Composer\Autoload\ClassLoader::initializeIncludeClosure():575}

C:\Users\cardo\Herd\obmsistem\vendor\composer\ClassLoader.php
:427
loadClass

App\Http\Controllers\Admin\OrcamentoController
:303
criarOrcamentoPrestador

App\Http\Controllers\Admin\OrcamentoController
:271
criarRegistroEspecifico

App\Http\Controllers\Admin\OrcamentoController
:132
store

Illuminate\Routing\ControllerDispatcher
:46
dispatch

Illuminate\Routing\Route
:265
runController

Illuminate\Routing\Route
:211
run

Illuminate\Routing\Router
:822
{closure:Illuminate\Routing\Router::runRouteWithinStack():821}

Illuminate\Pipeline\Pipeline
:180
{closure:Illuminate\Pipeline\Pipeline::prepareDestination():178}

App\Http\Middleware\AdminAuth
:37
handle

Illuminate\Pipeline\Pipeline
:219
{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}

Illuminate\Routing\Middleware\SubstituteBindings
:50
handle

Illuminate\Pipeline\Pipeline
:219
{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}

Illuminate\Foundation\Http\Middleware\VerifyCsrfToken
:87
handle

Illuminate\Pipeline\Pipeline
:219
{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}

Illuminate\View\Middleware\ShareErrorsFromSession
:48
handle

Illuminate\Pipeline\Pipeline
:219
{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}

Illuminate\Session\Middleware\StartSession
:120
handleStatefulRequest

Illuminate\Session\Middleware\StartSession
:63
handle

Illuminate\Pipeline\Pipeline
:219
{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}

Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse
:36
handle

Illuminate\Pipeline\Pipeline
:219
{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}

Illuminate\Cookie\Middleware\EncryptCookies
:74
handle

Illuminate\Pipeline\Pipeline
:219
{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}

Illuminate\Pipeline\Pipeline
:137
then

Illuminate\Routing\Router
:821
runRouteWithinStack

Illuminate\Routing\Router
:800
runRoute

Illuminate\Routing\Router
:764
dispatchToRoute

Illuminate\Routing\Router
:753
dispatch

Illuminate\Foundation\Http\Kernel
:200
{closure:Illuminate\Foundation\Http\Kernel::dispatchToRouter():197}

Illuminate\Pipeline\Pipeline
:180
{closure:Illuminate\Pipeline\Pipeline::prepareDestination():178}

Illuminate\Foundation\Http\Middleware\TransformsRequest
:21
handle

Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull
:31
handle

Illuminate\Pipeline\Pipeline
:219
{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}

Illuminate\Foundation\Http\Middleware\TransformsRequest
:21
handle

Illuminate\Foundation\Http\Middleware\TrimStrings
:51
handle

Illuminate\Pipeline\Pipeline
:219
{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}

Illuminate\Http\Middleware\ValidatePostSize
:27
handle

Illuminate\Pipeline\Pipeline
:219
{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}

Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance
:109
handle

Illuminate\Pipeline\Pipeline
:219
{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}

Illuminate\Http\Middleware\HandleCors
:48
handle

Illuminate\Pipeline\Pipeline
:219
{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}

Illuminate\Http\Middleware\TrustProxies
:58
handle

Illuminate\Pipeline\Pipeline
:219
{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}

Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks
:22
handle

Illuminate\Pipeline\Pipeline
:219
{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}

Illuminate\Http\Middleware\ValidatePathEncoding
:26
handle

Illuminate\Pipeline\Pipeline
:219
{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}

Illuminate\Pipeline\Pipeline
:137
then

Illuminate\Foundation\Http\Kernel
:175
sendRequestThroughRouter

Illuminate\Foundation\Http\Kernel
:144
handle

Illuminate\Foundation\Application
:1219
handleRequest

C:\Users\cardo\Herd\obmsistem\public\index.php
:20
require
1 vendor frame collapsed
C:\Users\cardo\Herd\obmsistem\app\Models\OrcamentoPrestador.php :1
<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class OrcamentoPrestador extends Model
{
    protected $table = 'orcamento_prestador';
 
    protected $fillable = [
        'orcamento_id',
        'fornecedor_omie_id',
        'fornecedor_nome',
        'valor_referencia',
        'qtd_dias',
Request
POST /admin/orcamentos
Headers
cookie
XSRF-TOKEN=eyJpdiI6InZpWnJYblM2YTZXSXRGNldlN2VJY0E9PSIsInZhbHVlIjoib3dtWnhDNlRwK1ppYTJ0NXVmdDF6czYvV0hCNlFZaklXajUzKzM4NWxWSUEzWDBsSk9EdVVpZ1lZb0JHZWRObWcwSHRMZk53QjhsLzhxWjBQdWtKUHZ3TzFsRCtBdlRpcXAzK0JHYUY3eVdFRmRNYUE5Qk1MbHlPUHV2RGM1a1UiLCJtYWMiOiI3YjRhYjU3NDE0OGVmYjc2YjI0NDk0YWQ4ODc3ZjEyNjA5ZDlhOGE2YWE4MjRmNDBhMzA4NzcyNGJmMzA2ZmRjIiwidGFnIjoiIn0%3D; laravel-session=eyJpdiI6IkVwUkhUQ0IvM05oQVRya1V6OTBGRUE9PSIsInZhbHVlIjoiK0RHcFp2WFhHSEtmMndWRk5HZnFaUjFmVkVmVU03c0RPUk52TVE0bmlVaTNGSUdXQndEc2Z5bng4WmhEYmtEeG5LZkhuMTNiT2o4dmFjRDhCRTBMM2dwcVIvVy9abXY0YTBCMkZTcThYVnZCVWhpU2wvYUFoUlAvdjFpZUUzckUiLCJtYWMiOiJlOWEwYjdjYjM3Zjk3NDYzZDU5ODNmYjQzOGNhMmRlZjc2NWI4MGNlYzBiY2IyODBiOTE4NWM0MWZiOWRlMDAxIiwidGFnIjoiIn0%3D
accept-language
pt-BR,pt;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6
accept-encoding
gzip, deflate
referer
http://obmsistem.test/admin/orcamentos/create
accept
text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
user-agent
Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0
upgrade-insecure-requests
1
content-type
application/x-www-form-urlencoded
origin
http://obmsistem.test
cache-control
max-age=0
content-length
741
connection
keep-alive
host
obmsistem.test
Body
{
    "_token": "AleM0MgPXJm8XKM4R0zrftkOtVMlUI6nJL4uFmsh",
    "data_solicitacao": "2025-08-26",
    "centro_custo_id": "3",
    "numero_orcamento": "Gerado automaticamente",
    "tipo_orcamento": "prestador",
    "nome_rota": "Boa Vista",
    "id_logcare": "12345678",
    "cliente_omie_id": "3337758798",
    "cliente_nome": "DIAGNOSTICOS DA AMERICA S.A .",
    "horario": "20:00",
    "frequencia_atendimento": [
        "segunda",
        "quarta",
        "domingo"
    ],
    "status": "enviado",
    "km_dia": null,
    "qtd_dias_aumento": null,
    "combustivel_km_litro": null,
    "valor_combustivel": null,
    "hora_extra": null,
    "nova_origem": null,
    "novo_destino": null,
    "distancia_km_proprio": null,
    "valor_km_proprio": null,
    "fornecedor_omie_id": "3337758818",
    "fornecedor_nome": "QUEST DIAGNOSTICS DO BRASIL LTDA",
    "valor_referencia": "100",
    "qtd_dias": "5",
    "lucro_percentual": "10",
    "grupo_imposto_id": "7",
    "impostos_percentual": "16",
    "observacoes": null
}
Application
Routing
controller
App\Http\Controllers\Admin\OrcamentoController@store
route name
admin.orcamentos.store
middleware
web, admin.auth
Database Queries
mysql (98.64 ms)
select * from `sessions` where `id` = '0YxoTn9Zr44wgzUaep9g3EDd3DVrggnJVnDNHraK' limit 1
mysql (24.95 ms)
select * from `users` where `id` = 2 limit 1
mysql (37.68 ms)
select count(*) as aggregate from `centros_custo` where `id` = '3'
mysql (27.17 ms)
select count(*) as aggregate from `grupos_impostos` where `id` = '7'
mysql (111.61 ms)
insert into `orcamentos` (`data_solicitacao`, `centro_custo_id`, `nome_rota`, `id_logcare`, `cliente_omie_id`, `cliente_nome`, `horario`, `tipo_orcamento`, `observacoes`, `frequencia_atendimento`, `user_id`, `data_orcamento`, `numero_orcamento`, `updated_at`, `created_at`) values ('2025-08-26 00:00:00', '3', 'Boa Vista', '12345678', '3337758798', 'DIAGNOSTICOS DA AMERICA S.A .', '20:00', 'prestador', NULL, '["segunda","quarta","domingo"]', 2, '2025-08-26 00:00:00', 'ORC-20250826063637', '2025-08-26 06:36:37', '2025-08-26 06:36:37')