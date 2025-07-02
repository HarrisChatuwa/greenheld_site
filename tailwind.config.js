/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./public/**/*.{html,js,php}"],
  theme: {
    extend: {
      colors: {
        'primary': {
          light: '#67e8f9', // Lighter cyan
          DEFAULT: '#315039', // Cyan 600
          dark: '#0e7490',  // Darker cyan
        },
        'secondary': {
          light: '#fde68a', // Lighter yellow
          DEFAULT: '#facc15', // Yellow 500
          dark: '#ca8a04',   // Darker yellow
        },
        'neutral': {
          light: '#f3f4f6', // Gray 100
          DEFAULT: '#6b7280', // Gray 500
          dark: '#1f2937',   // Gray 800
        },
        'accent': {
          DEFAULT: '#ec4899', // Pink 500
        },
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', '"Helvetica Neue"', 'Arial', '"Noto Sans"', 'sans-serif', '"Apple Color Emoji"', '"Segoe UI Emoji"', '"Segoe UI Symbol"', '"Noto Color Emoji"'],
        serif: ['Georgia', 'Cambria', '"Times New Roman"', 'Times', 'serif'],
      },
    },
  },
  plugins: [],
}
