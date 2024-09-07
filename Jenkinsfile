pipeline {
    agent any  // Utilise n'importe quel agent disponible

    stages { 
        stage('Clone') {
            steps {
                // Clone le dépôt Git de la branche 'main'
		git branch: 'main', url: 'https://github.com/Tazxor/conge-2024.git'
            }
        }

        stage('Contrôle qualité') {
            steps {
                // Exécute SonarQube pour l'analyse de la qualité du code
                sh '''
                # Ajoute sonarqube_project et sonarqube_token à la configuration de pipeline de Jenkins
              sonar-scanner \
                -Dsonar.projectKey=conge_taylan \
                -Dsonar.sources=. \
                -Dsonar.host.url=http://192.168.1.24:9000 \
                -Dsonar.token=sqp_252778c443ac4f2d03549c7a7c208c3592623529
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

