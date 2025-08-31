Sun Aug 31 10:09:12 UTC 2025
🚀 Iniciando deploy...
📥 Fazendo pull do repositório...
From github.com:cardosoads/obmsistem
 * branch            main       -> FETCH_HEAD
   d12b3ad..1240551  main       -> origin/main
Updating d12b3ad..1240551
Fast-forward
 bkp_orcamentos/Error.md                            | 376 +++++----------------
 ...01_27_000001_optimize_centros_custo_indexes.php |  37 +-
 2 files changed, 108 insertions(+), 305 deletions(-)
🧹 Limpando caches antigos...
📦 Instalando dependências do Composer...
Installing dependencies from lock file
Verifying lock file contents can be installed on current platform.
Nothing to install, update or remove
Generating optimized autoload files
> @php artisan package:discover --ansi

   INFO  Discovering packages.  

  barryvdh/laravel-dompdf ............................................... DONE
  laravel/tinker ........................................................ DONE
  nesbot/carbon ......................................................... DONE
  nunomaduro/termwind ................................................... DONE

> @php artisan clear-compiled

   INFO  Compiled services and packages files removed successfully.  

> @php artisan config:clear

   INFO  Configuration cache cleared successfully.  

54 packages you are using are looking for funding.
Use the `composer fund` command to find out more!
🔄 Regenerando autoload...
Generating optimized autoload files
> @php artisan package:discover --ansi

   INFO  Discovering packages.  

  barryvdh/laravel-dompdf ............................................... DONE
  laravel/tinker ........................................................ DONE
  nesbot/carbon ......................................................... DONE
  nunomaduro/termwind ................................................... DONE

> @php artisan clear-compiled

   INFO  Compiled services and packages files removed successfully.  

> @php artisan config:clear

   INFO  Configuration cache cleared successfully.  

Generated optimized autoload files containing 4929 classes
⚡ Limpando caches do Laravel...

   INFO  Configuration cache cleared successfully.  


   INFO  Application cache cleared successfully.  


   INFO  Route cache cleared successfully.  


   INFO  Compiled views cleared successfully.  


   INFO  Compiled services and packages files removed successfully.  

🔧 Instalando dependências do NPM...

added 93 packages, and audited 94 packages in 2s

22 packages are looking for funding
  run `npm fund` for details

found 0 vulnerabilities
🏗️ Compilando assets...

> build
> vite build

vite v7.1.2 building for production...
transforming...
✓ 54 modules transformed.
rendering chunks...
computing gzip size...
public/build/manifest.json             0.31 kB │ gzip:  0.17 kB
public/build/assets/app-DLjtT7B-.css  83.51 kB │ gzip: 12.16 kB
public/build/assets/app-DlXmOVrS.js   40.05 kB │ gzip: 15.83 kB
✓ built in 571ms
🔄 Executando migrações...

   INFO  Running migrations.  

  2025_01_27_000001_optimize_centros_custo_indexes ............... 2.23ms DONE
  2025_01_27_000002_add_id_protocolo_to_orcamentos_table ......... 4.02ms FAIL

In Connection.php line 824:
                                                                               
  SQLSTATE[42S02]: Base table or view not found: 1146 Table 'obm.orcamentos'   
  doesn't exist (Connection: mysql, SQL: alter table `orcamentos` add `id_pro  
  tocolo` varchar(255) null comment 'ID de Protocolo digitado pelo usuário' a  
  fter `centro_custo_id`)                                                      
                                                                               

In Connection.php line 570:
                                                                               
  SQLSTATE[42S02]: Base table or view not found: 1146 Table 'obm.orcamentos'   
  doesn't exist                                                                
                                                                               
