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

Then commit changes:

    git add .
    git commit -am "Added docker devsetup."


## Credits

* Based upon the setup provided by amazee.io, see 
  https://github.com/amazeeio/drupal-example
* (c) 2018 drunomics GmbH, GNU GPLv2+
