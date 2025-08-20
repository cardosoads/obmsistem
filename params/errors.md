Internal Server Error

Illuminate\Foundation\ViteManifestNotFoundException
Vite manifest not found at: C:\Users\cardo\Herd\sistemaobm\public\build/manifest.json
GET sistemaobm.test
PHP 8.4.11 — Laravel 12.24.0
C:\Users\cardo\Herd\sistemaobm\vendor\laravel\framework\src\Illuminate\Foundation\Vite.php :935
    {
        $path = $this->manifestPath($buildDirectory);
 
        if (! isset(static::$manifests[$path])) {
            if (! is_file($path)) {
                throw new ViteManifestNotFoundException("Vite manifest not found at: $path");
            }
 
            static::$manifests[$path] = json_decode(file_get_contents($path), true);
        }
 
        return static::$manifests[$path];
    }
 
    /**
     * Get the path to the manifest file for the given build directory.
     *
 
Request
GET /login
Headers
cookie
XSRF-TOKEN=eyJpdiI6IjJIc21Ybmtkc2hNOXJGcGpHWVhYTkE9PSIsInZhbHVlIjoiL0xRaXByWlRLbG9pMzlPL21vcFZiamU3SHhteHc5d1c1T0xRUVMxbW9JRlU5Z2R3NlZWRmtSZGw4SEtBakZBMFU3WVY3N2dUYlRWVTVKRVZRK1lVMng2UkZ2d1F1WkQvallTV1M1eXF2Q2tzSk1Sd1IweWl3VHZjdmlZOXFxZUEiLCJtYWMiOiI1NjIzMGYyMDdhY2FjMmEzMjZjMjg0Y2RmMDNmMTJhMDJkMzhlMTMyM2M4NzUwMDVmZTIxN2U2OTE1NmNkOTM2IiwidGFnIjoiIn0%3D; laravel_session=eyJpdiI6IjBNV3NYNTFzNW02dFhYVDM5TTJFbXc9PSIsInZhbHVlIjoiZTAzSlQ3UFN5eWYwM3F6K29PZVM3M0NYbGMyS2JjV0hJS3dkb00rK2kwZVUzYlREdmE2QjVVWFRKZGYrQW9KVU11VHBLM1RUL3d5azRqMzY0VDlieTNob0t6T0pMbDZsL1YvMitic0hwbkNDU0o4Z0RqNXBCRzN2S01nTThtMTQiLCJtYWMiOiI1YmY1OTBmNTNmZjYzNjNjYmY1OTQzNzlhODcyNjQ4NWIxN2Q1NDBjMWRmZjQwYTA4ZWRmMThmYTk3ZmQ4ZGJjIiwidGFnIjoiIn0%3D
accept-language
en-US
accept-encoding
gzip, deflate
accept
text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
user-agent
Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Trae/1.100.3 Chrome/132.0.6834.210 Electron/34.5.1 Safari/537.36
upgrade-insecure-requests
1
connection
keep-alive
host
sistemaobm.test
Body
No body data
Application
Routing
controller
App\Http\Controllers\Auth\AuthenticatedSessionController@create
route name
login
middleware
web, guest
Database Queries
sqlite
select * from "sessions" where "id" = 'jz4iMzk8z5vgEtCzgHEtG3kyb45VgBZKaMqKVUvH' limit 1