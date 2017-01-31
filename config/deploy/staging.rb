server 'stage.aidstream.org',user: 'aidstream', roles: %w{web app db}

# Directory to deploy
# ===================
set :env, 'staging'
set :deploy_to, '/home/aidstream/aidstream'
set :shared_path, '/home/aidstream/aidstream/shared'
set :overlay_path, '/home/aidstream/overlay'
set :tmp_dir, '/home/aidstream/tmp'
set :app_env, 'production'
set :app_debug, 'false'
set :session_driver, 'file'
set :cache_driver, 'file'
set :app_key, 'l1lMXZo3XSW77M1cXOlexSIdSPnb4ATW'
