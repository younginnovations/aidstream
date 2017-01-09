server '104.236.75.41',user: 'newstage', roles: %w{web app db}

# Directory to deploy
# ===================
set :env, 'staging'
set :deploy_to, '/home/newstage/newstage'
set :shared_path, '/home/newstage/newstage/shared'
set :overlay_path, '/home/newstage/overlay'
set :tmp_dir, '/home/newstage/tmp'
set :app_env, 'production'
set :app_debug, 'false'
set :session_driver, 'file'
set :cache_driver, 'file'
set :app_key, 'l1lMXZo3XSW77M1cXOlexSIdSPnb4ATW'
