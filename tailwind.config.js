/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./public/**/*.html",
  ],
  theme: {
    extend: {
      colors: {
        // Cores primárias baseadas na paleta fornecida
        primary: {
          50: '#f0f4f8',   // Muito claro
          100: '#d9e2ec',  // Claro
          200: '#bcccdc',  // Médio claro
          300: '#9fb3c8',  // Médio
          400: '#829ab1',  // Médio escuro
          500: '#648299',  // Base médio
          600: '#4a6741',  // Escuro
          700: '#3a5233',  // Mais escuro
          800: '#2a3d26',  // Muito escuro
          900: '#1E3951',  // Cor principal (azul escuro)
          950: '#162b3d',  // Extremamente escuro
        },
        // Cores de destaque baseadas no laranja
        accent: {
          50: '#fef7ed',   // Muito claro
          100: '#fdedd3',  // Claro
          200: '#fbd9a5',  // Médio claro
          300: '#f9c16d',  // Médio
          400: '#f7a935',  // Médio escuro
          500: '#F8AB14',  // Cor principal (laranja)
          600: '#e09000',  // Escuro
          700: '#b87000',  // Mais escuro
          800: '#945700',  // Muito escuro
          900: '#7a4600',  // Extremamente escuro
          950: '#4a2a00',  // Ultra escuro
        },
        // Tons neutros para fundo e textos
        neutral: {
          50: '#fafafa',   // Branco quase puro
          100: '#f5f5f5',  // Cinza muito claro
          200: '#e5e5e5',  // Cinza claro
          300: '#d4d4d4',  // Cinza médio claro
          400: '#a3a3a3',  // Cinza médio
          500: '#737373',  // Cinza
          600: '#525252',  // Cinza escuro
          700: '#404040',  // Cinza muito escuro
          800: '#262626',  // Quase preto
          900: '#171717',  // Preto
        },
        // Estados de sucesso, erro, aviso
        success: {
          50: '#f0fdf4',
          100: '#dcfce7',
          200: '#bbf7d0',
          300: '#86efac',
          400: '#4ade80',
          500: '#22c55e',
          600: '#16a34a',
          700: '#15803d',
          800: '#166534',
          900: '#14532d',
        },
        error: {
          50: '#fef2f2',
          100: '#fee2e2',
          200: '#fecaca',
          300: '#fca5a5',
          400: '#f87171',
          500: '#ef4444',
          600: '#dc2626',
          700: '#b91c1c',
          800: '#991b1b',
          900: '#7f1d1d',
        },
        warning: {
          50: '#fffbeb',
          100: '#fef3c7',
          200: '#fde68a',
          300: '#fcd34d',
          400: '#fbbf24',
          500: '#f59e0b',
          600: '#d97706',
          700: '#b45309',
          800: '#92400e',
          900: '#78350f',
        },
      },
      fontFamily: {
        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      boxShadow: {
        'primary': '0 4px 14px 0 rgba(30, 57, 81, 0.15)',
        'accent': '0 4px 14px 0 rgba(248, 171, 20, 0.15)',
        'soft': '0 2px 8px 0 rgba(0, 0, 0, 0.05)',
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'slide-up': 'slideUp 0.3s ease-out',
        'pulse-soft': 'pulseSoft 2s infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(10px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        pulseSoft: {
          '0%, 100%': { opacity: '1' },
          '50%': { opacity: '0.8' },
        },
      },
    },
  },
  plugins: [],
};