server '104.236.75.41',user: 'aidstream', roles: %w{web app db}

# Directory to deploy
# ===================
set :env, 'prod'
set :deploy_to, '/home/aidstream/app/staging'
set :shared_path, '/home/aidstream/app/staging/shared'
set :overlay_path, '/home/aidstream/app/overlay'
set :app_env, 'production'
set :app_debug, 'false'
set :session_driver, 'file'
set :cache_driver, 'file'
set :app_key, 'mybRtRee32Wn6r98OwpQNR71B3jTeLL9'
