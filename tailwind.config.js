module.exports = {
  purge: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {

    container: {
      center: true,
      padding: '1rem',
    },

    colors: {
      'transparent': 'transparent',

      'black': '#22292f',
      'grey-darkest': '#3d4852',
      'grey-darker': '#606f7b',
      'grey-dark': '#8795a1',
      'grey': '#b8c2cc',
      'grey-light': '#dae1e7',
      'grey-lighter': '#f1f5f8',
      'grey-lightest': '#f8fafc',
      'white': '#ffffff',

      'red-darkest': '#3b0d0c',
      'red-darker': '#621b18',
      'red-dark': '#cc1f1a',
      'red': '#e3342f',
      'red-light': '#ef5753',
      'red-lighter': '#f9acaa',
      'red-lightest': '#fcebea',

      'orange-darkest': '#462a16',
      'orange-darker': '#613b1f',
      'orange-dark': '#de751f',
      'orange': '#f6993f',
      'orange-light': '#faad63',
      'orange-lighter': '#fcd9b6',
      'orange-lightest': '#fff5eb',

      'yellow-darkest': '#453411',
      'yellow-darker': '#684f1d',
      'yellow-dark': '#f2d024',
      'yellow': '#ffed4a',
      'yellow-light': '#fff382',
      'yellow-lighter': '#fff9c2',
      'yellow-lightest': '#fcfbeb',

      'green-darkest': '#0f2f21',
      'green-darker': '#1a4731',
      'green-dark': '#1f9d55',
      'green': '#38c172',
      'green-light': '#51d88a',
      'green-lighter': '#a2f5bf',
      'green-lightest': '#e3fcec',

      'teal-darkest': '#0d3331',
      'teal-darker': '#20504f',
      'teal-dark': '#38a89d',
      'teal': '#4dc0b5',
      'teal-light': '#64d5ca',
      'teal-lighter': '#a0f0ed',
      'teal-lightest': '#e8fffe',

      'blue-darkest': '#12283a',
      'blue-darker': '#1c3d5a',
      'blue-dark': '#2779bd',
      'blue': '#3490dc',
      'blue-light': '#6cb2eb',
      'blue-lighter': '#bcdefa',
      'blue-lightest': '#eff8ff',

      'indigo-darkest': '#191e38',
      'indigo-darker': '#2f365f',
      'indigo-dark': '#5661b3',
      'indigo': '#6574cd',
      'indigo-light': '#7886d7',
      'indigo-lighter': '#b2b7ff',
      'indigo-lightest': '#e6e8ff',

      'purple-darkest': '#21183c',
      'purple-darker': '#382b5f',
      'purple-dark': '#794acf',
      'purple': '#9561e2',
      'purple-light': '#a779e9',
      'purple-lighter': '#d6bbfc',
      'purple-lightest': '#f3ebff',

      'pink-darkest': '#451225',
      'pink-darker': '#6f213f',
      'pink-dark': '#eb5286',
      'pink': '#f66d9b',
      'pink-light': '#fa7ea8',
      'pink-lighter': '#ffbbca',
      'pink-lightest': '#ffebef',
    },

    backgroundColor: theme => theme('colors'),
    textColor: theme => theme('colors'),
    borderColor: theme => ({
      DEFAULT: theme('colors.grey-light'),
      ...theme('colors'),
    }),

    screens: {
      'sm': '480px',
      'md': '640px',
      'lg': '992px',
      'xl': '1200px',
    },

    fontFamily: {
      'sans': [
        'Nunito',
        'system-ui',
        'sans-serif',
      ],
      'serif': [
        'Constantia',
        'Liberation Serif',
        'Georgia',
        'serif',
      ],
      'mono': [
        'Menlo',
        'Monaco',
        'Consolas',
        'Liberation Mono',
        'Courier New',
        'monospace',
      ],
    },

    extends: {
      minWidth: {
        '1/2': '50%',
      },
      maxWidth: {
        '64': '16rem',
      },
      maxHeight: {
        '64': '16rem',
        '96': '24rem',
        '128': '32rem',
        '40screen': '40vh',
        '50screen': '50vh',
      },
    }
  },

  variants: {
    display: ['responsive', 'group-hover'],
    margin: ['responsive', 'first', 'last'],
    padding: ['responsive', 'first', 'last'],
    fontWeight: ['focus', 'hover'],
    textOverflow: ['responsive'],
    borderColor: ['responsive', 'hover', 'focus']
  },
  plugins: [
    require('@tailwindcss/forms')
  ]
}
