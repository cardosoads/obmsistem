Sun Aug 31 10:00:30 UTC 2025
🚀 Iniciando deploy...
📥 Fazendo pull do repositório...
From github.com:cardosoads/obmsistem
 * branch            main       -> FETCH_HEAD
   934252c..d12b3ad  main       -> origin/main
Updating 934252c..d12b3ad
Fast-forward
 ...01_27_000001_optimize_centros_custo_indexes.php | 27 +++++++++++++++++-----
 1 file changed, 21 insertions(+), 6 deletions(-)
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
public/build/manifest.json             0.31 kB │ gzip:  0.16 kB
public/build/assets/app-sCgacLrN.css  83.48 kB │ gzip: 12.16 kB
public/build/assets/app-DlXmOVrS.js   40.05 kB │ gzip: 15.83 kB
✓ built in 572ms
🔄 Executando migrações...

   INFO  Running migrations.  

  2025_01_27_000001_optimize_centros_custo_indexes .............. 11.04ms FAIL

In Connection.php line 824:
                                                                               
  SQLSTATE[42S02]: Base table or view not found: 1146 Table 'obm.centros_cust  
  o' doesn't exist (Connection: mysql, SQL: SHOW INDEX FROM centros_custo)     
                                                                               

In Connection.php line 411:
                                                                               
  SQLSTATE[42S02]: Base table or view not found: 1146 Table 'obm.centros_cust  
  o' doesn't exist               