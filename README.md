# docker-compose devsetup

A simple devsetup based upon docker-compose. The devsetup makes use of the
drunomics docker images by default.

## Usage

The variables in the .env file are defaults and should be overridden by
environment variables that are sourced in the shell; e.g.:

    source dotenv/loader.sh
    cd devsetup
    docker-composer up -d
    
Alternatively, this can be handled by a wrapper script, of course.

## Configuration

Refer to the commented environment variables in the `.env` file.

## Credits

(c) 2018 drunomics GmbH. /  MIT License
