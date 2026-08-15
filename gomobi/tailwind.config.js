/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{js,ts,jsx,tsx}'],
  theme: {
    extend: {
      colors: {
        // GoMobi brand palette — keep exactly as specified, do not add new hues
        'gomobi-blue': '#ACD1FD',
        'gomobi-purple': '#988EE9',
        'gomobi-dark': '#21222D',
      },
      fontFamily: {
        heading: ['Poppins', 'sans-serif'],
        body: ['Lato', 'sans-serif'],
      },
      boxShadow: {
        subtle: '0 1px 2px 0 rgba(33, 34, 45, 0.04), 0 1px 3px 0 rgba(33, 34, 45, 0.06)',
        card: '0 1px 3px 0 rgba(33, 34, 45, 0.06)',
        dropdown: '0 4px 16px 0 rgba(33, 34, 45, 0.12)',
      },
      borderRadius: {
        DEFAULT: '8px',
      },
    },
  },
  plugins: [],
}
