## WebCanyon WCron Manager 

This project is a cron manager for Symfony applications or used like a standalone cron manager.   

## For making this to function you will need follow this steps:

#### Step 1
Go to your symfony project and run:

```bash
./bin/console wcron:install
```
Insert root password for creating symlink to /usr/local/bin/wcron 


#### Step2
Go to terminal and run this command:

```bash
crontab -e
```

Paste the text from below and save changes

```bash
* * * * * /usr/local/bin/wcron /var/log/wcron.log 2>&1
```

Author Catalin Radoi