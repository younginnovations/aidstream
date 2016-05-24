server '104.236.75.41',user: 'demo', roles: %w{web app db}

# Directory to deploy
# ===================
set :env, 'demo'
set :deploy_to, '/home/demo/app/'
set :shared_path, '/home/demo/app/shared'
set :overlay_path, '/home/demo/overlay'
set :tmp_dir, '/home/demo/tmp'
set :app_env, 'production'
set :app_debug, 'false'
set :session_driver, 'file'
set :cache_driver, 'file'
set :app_key, 'mybRtRee32Wn6r98OwpQNR71B3jTeLL9'
