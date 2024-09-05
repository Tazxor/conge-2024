pipeline {
    agent any

    stages {
        stage('Clone') {
            steps {              
		git branch: 'main', url: 'https://github.com/Tazxor/conge-2024.git'
            }
        }
        stage('Contrôle qualité') {
            steps {
                sh '''
                # Add sonarqube_project and sonarqube_token to the Jenkins configuration pipeline
                sonar-scanner \
                -Dsonar.projectKey=taylan_conge \
                -Dsonar.sources=. \
                -Dsonar.host.url=http://192.168.1.24:9000 \
                -Dsonar.token=sqp_a2c1c55df4c7d56dc08aee6965688f13df1378ba
                '''
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

