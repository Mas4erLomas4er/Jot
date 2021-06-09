const colors = require('tailwindcss/colors')

module.exports = {
    purge: {
        enabled: true,
        content: [
            './resources/**/*.blade.php',
            './resources/**/*.vue',
        ],
    },
    darkMode : false, // or 'media' or 'class'
    theme : {
        extend : {
            width: {
                '96': '24rem',
                '100': '25rem',
            },
            colors : {
                blue : colors.lightBlue,
            }
        }
    },
    variants : {
        extend : {}
    },
    plugins : []
}
