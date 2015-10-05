lock '3.4.0'

# Application #
#####################################################################################
set :application,     'aidstream'
set :branch,          ENV["branch"] || "master"
set :user,            ENV["user"] || ENV["USER"] || "aidstream"
set :tmp_dir,         '/home/yipl/tmp'

# SCM #
#####################################################################################
set :repo_url,        'git@gitlab.yipl.com.np:web-apps/aidstream-new.git'
set :scm,             :git
set :repo_base_url,   :'http://gitlab.yipl.com.np/'
set :repo_diff_path,  :'web-apps/aidstream-new/compare/master...'

# Multistage Deployment #
#####################################################################################
set :stages,              %w(dev staging prod)
set :default_stage,       "staging"

# Other Options #
#####################################################################################
set :ssh_options,         { :forward_agent => true }
set :default_run_options, { :pty => true }

# Permissions #
#####################################################################################
set :use_sudo,            false
set :permission_method,   :acl
set :use_set_permissions, true
set :webserver_user,      "www-data"
set :group,               "www-data"
set :keep_releases,       3

# Hipchat Integration #
#####################################################################################
set :hipchat_token,         "ZpXA6zeepyBgIm4R3EbImcmm7xCcXMl49NbbEpRg"
set :hipchat_room_name,     "1080583"
set :hipchat_announce,      false       # notify users?
set :hipchat_color,         'green'     # finished deployment message color
set :hipchat_failed_color,  'red'       # cancelled deployment message color

# Create ver.txt #
#######################################################################################
require 'date'
set :current_time, DateTime.now

namespace :environment do

    desc "Set environment variables"
    task :create_variables do
        on roles(:app) do
              puts ("--> Create enviroment configuration file")
              execute "cat /dev/null > #{shared_path}/.env"
              execute "echo APP_ENV=#{fetch(:app_env)} >> #{shared_path}/.env"
              execute "echo APP_DEBUG=false >> #{shared_path}/.env"
              execute "echo APP_KEY=#{fetch(:app_key)} >> #{shared_path}/.env"
              execute "echo DB_HOST=%db_host% >> #{shared_path}/.env"
              execute "echo DB_DATABASE=%db_name% >> #{shared_path}/.env"
              execute "echo DB_USERNAME=%db_username% >> #{shared_path}/.env"
              execute "echo DB_PASSWORD=%db_password% >> #{shared_path}/.env"
              execute "echo LOGENTRIES_TOKEN=%logentries_token% >> #{shared_path}/.env"
              execute "echo CACHE_DRIVER=#{fetch(:cache_driver)} >> #{shared_path}/.env"
              execute "echo SESSION_DRIVER=#{fetch(:session_driver)} >> #{shared_path}/.env"
              execute "sed --in-place -f #{fetch(:overlay_path)}/parameters.sed #{shared_path}/.env"
        end
    end
end

namespace :composer do

    desc "Running Composer Install"
    task :install do
        on roles(:app) do
            within release_path do
                execute :composer, "install --no-dev --quiet"
                execute :composer, "dumpautoload"
            end
        end
    end
end

namespace :aidstream do

    desc "Create shared folders"
    task :create_storage_folder do
        on roles(:all) do
            execute "mkdir -p #{shared_path}/storage"
            execute "mkdir #{shared_path}/storage/app"
            execute "mkdir #{shared_path}/storage/framework"
            execute "mkdir #{shared_path}/storage/framework/cache"
            execute "mkdir #{shared_path}/storage/framework/sessions"
            execute "mkdir #{shared_path}/storage/framework/views"
            execute "mkdir #{shared_path}/storage/logs"
            execute :chmod, "-R 777 #{shared_path}/storage"
        end
    end
    task :create_uploads_folder do
        on roles(:all) do
            execute "mkdir #{shared_path}/uploads"
            execute "mkdir #{shared_path}/uploads/files"
            execute "mkdir #{shared_path}/uploads/files/organization"
            execute "mkdir #{shared_path}/uploads/files/activity"
            execute "mkdir #{shared_path}/uploads/temp"
            execute :chmod, "-R 777 #{shared_path}/uploads/"
        end
    end

    desc "Symbolic link for shared folders"
    task :create_symlink do
        on roles(:app) do
            within release_path do
                execute "rm -rf #{release_path}/storage"
                execute "ln -s #{shared_path}/storage/ #{release_path}"
                execute "ln -s #{shared_path}/uploads #{release_path}/public"
                execute "ln -s #{shared_path}/.env #{release_path}"
            end
        end
    end

    desc "Run Laravel Artisan migrate task."
    task :migrate do
        on roles(:app) do
            within release_path do
                execute :php, "artisan migrate --force"
            end
        end
    end

    desc "Run Laravel Artisan seed task."
    task :seed do
        on roles(:app) do
            within release_path do
            execute :php, "artisan db:seed --force"
            end
        end
    end

    desc "Optimize Laravel Class Loader"
    task :optimize do
        on roles(:app) do
            within release_path do
                execute :php, "artisan clear-compiled"
                execute :php, "artisan optimize"
            end
        end
    end

    task :create_ver_txt do
        on roles(:all) do
            puts ("--> Creating ver.txt at base URL")
            execute "echo Date: #{fetch(:current_time)} >> #{release_path}/public/ver.txt"
            execute "echo Branch: #{fetch(:branch)} >> #{release_path}/public/ver.txt"
            execute "echo Revision: #{fetch(:current_revision)} >> #{release_path}/public/ver.txt"
            execute "echo Deployed by: #{fetch(:user)} >> #{release_path}/public/ver.txt"
            execute "find #{release_path} -type f -name 'ver.txt' -exec chmod 664 {} \\;"
        end
    end

    desc " Set up project "
    task :set_up do
        on roles(:all) do
            invoke "aidstream:create_storage_folder"
            invoke "aidstream:create_uploads_folder"
            invoke "environment:create_variables"
        end
    end
end

namespace :hipchat do

    desc 'Hipchat notification on deployment'
    task :start do
        on roles(:all) do
            message  = "#{fetch(:user)} is deploying #{fetch(:application)}/#{fetch(:branch)} to #{fetch(:env)}. diff at: #{fetch(:repo_base_url)}#{fetch(:repo_diff_path)}#{fetch(:branch)}"
            client = HipChat::Client.new(fetch(:hipchat_token), :api_version => 'v2')
            client[fetch(:hipchat_room_name)].send(fetch(:user), message)
        end
    end

    task :deployed do
        on roles(:all) do
            message  = "#{fetch(:user)} finished deploying #{fetch(:application)}/#{fetch(:branch)} (revision #{fetch(:current_revision)}) to #{fetch(:env)}."
            client = HipChat::Client.new(fetch(:hipchat_token), :api_version => 'v2')
            client[fetch(:hipchat_room_name)].send(fetch(:user), message, :color => 'green', :notify =>true)
        end
    end

    task :notify_deploy_reverted do
        on roles(:all) do
            message  = "Error deploying #{fetch(:application)}/#{fetch(:branch)} (revision #{fetch(:current_revision)}) to #{fetch(:env)}, user: #{fetch(:user)} ."
            client = HipChat::Client.new(fetch(:hipchat_token), :api_version => 'v2')
            client[fetch(:hipchat_room_name)].send(fetch(:user), message, :color => 'red')
        end
    end
end

namespace :nginx do
    desc 'Reload nginx server'
        task :reload do
            on roles(:all) do
            execute :sudo, :service, "nginx reload"
        end
    end
end

namespace :deploy do
    after :starting, "hipchat:start"
    after :updated, "composer:install"
    after :updated, "environment:create_variables"
    after :published, "aidstream:create_symlink"
    after :finished, "hipchat:deployed"
    after :finished, "aidstream:create_ver_txt"
end

after "deploy",   "nginx:reload"
