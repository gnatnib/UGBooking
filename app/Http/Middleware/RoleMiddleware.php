<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class RoleMiddleware
    {
        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @param  string  $role
         * @return mixed
         */
        public function handle(Request $request, Closure $next)
        {
            // Periksa apakah user sudah login
            if (!Auth::check()) {
                return redirect('/login');
            }

            // Periksa role user
            if (Auth::user()->role_name !== 'superadmin') {
                 return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
            }

            return $next($request);
        }
    }
