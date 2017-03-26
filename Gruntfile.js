module.exports = function(grunt) {
  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    pot: {
      options: {
        text_domain: 'wc-stock-amount-report',
        dest: 'languages/',
        keywords: ['gettext', '__'],
      },
      files: {
        src:  ['classes/*.php'],
        expand: true,
      }
    }
  });

  grunt.loadNpmTasks('grunt-pot');
};
