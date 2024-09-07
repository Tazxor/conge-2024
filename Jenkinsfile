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
                -Dsonar.projectKey=taylan_conge \  # Identifiant du projet dans SonarQube
                -Dsonar.sources=. \  # Chemin vers les sources à analyser
                -Dsonar.host.url=http://192.168.1.24:9000 \  # URL du serveur SonarQube
                -Dsonar.token=sqp_a2c1c55df4c7d56dc08aee6965688f13df1378ba  # Token d'authentification pour SonarQube
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

