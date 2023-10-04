# wp-skeleton (rename wp-PROJECT once project is configured)

## Create a new project (remove this chapter once project is configured)

### 1. Copy all files of this repository on your new project

### 2. Edit files

- replace skeleton by your project name in docker/docker-build-images.sh
- replace skeleton by your project name in docker/local.yml
- replace wp-skeleton by your project repository in bin/sync-all.sh




if no need for webpack in project, remove or edit the following :
 - edit : docker/local.yml
 - edit : docker/docker-build-images.sh

That't it.

#### Tips

- If u want a wordpress version on your local, just ./start.sh

## Starting

```./start.sh```

## Stopping

```./stop.sh```

### Create wp

```bin/./setup-install-wp-latest.sh```
```bin/./setup-wp-config.sh```

