# Test Projekt für Wowza Guzzle Client

Testintegration und Behta Tests für den Wowza Guzzle Client

## Development

vagrant-Box mit docker stuff

    cd vagrant
    vagrant up
    vagrant ssh
    cd project/
    bin/start
    
evtl. muss initial einmal vagrant reload ausgeführt werden falls project
nicht gemappt wird

    # in Vagrant rein in den php container und ins projektverzeichnis
    bin/php bash
    cd wowza-client-test
    
    composer install
    
    # Behta Tests ausführen
    bin/behat
