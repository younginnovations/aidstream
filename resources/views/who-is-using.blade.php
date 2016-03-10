<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aidstream</title>
    <link rel="shortcut icon" type="image/png" sizes="16*16" href="images/favicon.png"/>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
@include('includes.header')
<section class="main-container">
    <div class="organisation-list-wrapper">
        <div class="col-md-12 text-center">
            <h1>{{ $organizationCount }} organisations are using AidStream</h1>

            <p>The organisations listed below are using AidStream to publish their aid data in IATI.</p>

            <div class="organisations-list width-900">
                <ul>{{--
                    <li><span>Aids Fonds - STOP AIDS NOW! - Soa Aids Nederland</span></li>
                    <li><img src="images/org/ic-adra.png" alt=""></li>
                    <li><img src="images/org/ic-awc.png" alt=""></li>
                    <li><img src="images/org/ic-amref-flying.png" alt=""></li>
                    <li><img src="images/org/ic-add.png" alt=""></li>
                    <li><img src="images/org/ic-add.png" alt=""></li>
                    <li><span>Agence Française de Développement</span></li>
                    <li><img src="images/org/ic-add.png" alt=""></li>
                    <li><img src="images/org/ic-add.png" alt=""></li>
                    <li><span>Aids Fonds - STOP AIDS NOW! - Soa Aids Nederland</span></li>
                    <li><img src="images/org/ic-add.png" alt=""></li>
                    <li><img src="images/org/ic-add.png" alt=""></li>
                    <li><img src="images/org/ic-add.png" alt=""></li>
                    <li><img src="images/org/ic-add.png" alt=""></li>--}}
                </ul>
            </div>
            <a href="#" class="load-more">Load More</a>
        </div>
    </div>
</section>
@include('includes.footer')

<div class="hidden">
    <ul class="no-image-logo">
        <li><span></span></li>
    </ul>
    <ul class="has-image-logo">
        <li><img/></li>
    </ul>
</div>

<link rel="stylesheet" href="css/style.css">
<script src="js/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var page = 0;
        var count = 12;

        $('.load-more').click(function (e) {
            e.preventDefault();
            $('body').append('<div class="loader">.....</div>');

            $.ajax({
                type: 'get',
                url: '{{ url('/who-is-using') }}' + '/' + page + '/' + count,
                success: function (data) {
                    if (!data.next_page) {
                        $('.load-more').addClass('hidden').remove();
                    }
                    var organizations = data.organizations;
                    var logos = '';
                    for (var i = 0; i < organizations.length; i++) {
                        var organization = organizations[i];
                        var logo = '';
                        if (organization.logo_url) {
                            logo = $('.has-image-logo').clone();
                            $('img', logo).attr({src: organization.logo_url, alt: organization.name});
                        } else {
                            logo = $('.no-image-logo').clone();
                            $('span', logo).html(organization.name);
                        }
                        logos += logo.html();
                    }
                    $('.organisations-list ul').append(logos);
                    page++;
                },
                complete: function () {
                    $('body > .loader').addClass('hidden').remove();
                }
            });
        });
        $('.load-more').trigger('click');
    });
</script>
<style type="text/css">
    .loader {
        position: fixed;
        left: 0px;
        right: 0px;
        top: 0px;
        bottom: 0px;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        color: #FFFFFF;
        text-align: center;
        font-size: 60px;
        letter-spacing: -16px;
        -webkit-animation: mymove 1s infinite; /* Chrome, Safari, Opera */
        -webkit-animation-direction: alternate;
        animation: loading 1s infinite;
        animation-direction: alternate;
    }

    /* Chrome, Safari, Opera */
    @-webkit-keyframes loading {
        from {
            letter-spacing: -16px;
        }
        to {
            letter-spacing: 16px;
        }
    }

    @keyframes loading {
        from {
            letter-spacing: -16px;
        }
        to {
            letter-spacing: 16px;
        }
    }
</style>
</body>
</html>
