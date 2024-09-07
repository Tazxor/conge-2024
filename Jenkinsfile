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
                -Dsonar.token=sqp_94eda49c8d88a6cfababd7bb2baa4b6298f6403c
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

