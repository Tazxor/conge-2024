pipeline {
    agent any

    stages {
        stage('Clone') {
            steps {              
		git branch: 'main', url: 'https://github.com/Tazxor/conge-2024.git'
            }
        }
        // stage('Install') {
        //     steps {
        //         sh '''
		//               composer update
        //               composer install
        //               rm composer.phar
        //         '''
        //     }
        // }
        // stage('Test') {
        //     steps {
        //         sh '''
        //             php bin/phpunit
        //         '''
        //     }
        // }
    }
}

