# drunomics docker devsetup

docker development setup based upon docker-compose. 

Features:
- utilizes docker-compose for local development (WIP)
- extendable to support drunomics' CI system - lupus-CI
- compatible to amazeeio.'s lagoon, just add https://github.com/drunomics/devsetup-amazeeio
 

## Setup

From your drupal-project root directory, run:

    git clone https://github.com/drunomics/devsetup-docker --branch=2.x devsetup-tmp
    rm -rf devsetup-tmp/.git devsetup-tmp/README.md
    cp -rfT devsetup-tmp .
        
    # Apply replacements and cleanup.
    php process-replacements.php
    rm -rf devsetup-tmp process-replacements.php
    
    echo 
    'COMPOSE_AMAZEEIO_VERSION=v0.22.1 
    COMPOSE_AMAZEEIO_PHP_VERSION=7.2
    ' >> env.defaults

Then commit changes:

    git add .
    git commit -am "Added docker devsetup."


If you are not using drunomics/drupal-project, rename "env.defaults" to ".env" - docker-compose picks the variables up. 

## Credits

* Based upon the setup provided by amazee.io, see 
  https://github.com/amazeeio/drupal-example
* (c) 2019 drunomics GmbH, GNU GPLv2+
