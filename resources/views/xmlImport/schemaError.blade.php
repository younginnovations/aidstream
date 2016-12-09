@extends('app')

@section('title', 'Imported XML Error')

@section('head')
    <script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
    <script type="text/javascript">
        function addLoadEvent(func) {
            var oldonload = window.onload;
            if (typeof window.onload != 'function') {
                window.onload = func;
            } else {
                window.onload = function () {
                    if (oldonload) {
                        oldonload();
                    }
                    func();
                }
            }
        }
    </script>
    <style type="text/css">
        /* Pretty printing styles. Used with prettify.js. */
        .str {
            color: #080;
        }

        .kwd {
            color: #008;
        }

        .com {
            color: #800;
        }

        .typ {
            color: #606;
        }

        .lit {
            color: #066;
        }

        .pun {
            color: #660;
        }

        .pln {
            color: #000;
        }

        .tag {
            color: #008;
        }

        .atn {
            color: #606;
        }

        .atv {
            color: #080;
        }

        .dec {
            color: #606;
        }

        pre.prettyprint {
            border: 1px solid #888;
        }

        @media print {
            .str {
                color: #060;
            }

            .kwd {
                color: #006;
                font-weight: bold;
            }

            .com {
                color: #600;
                font-style: italic;
            }

            .typ {
                color: #404;
                font-weight: bold;
            }

            .lit {
                color: #044;
            }

            .pun {
                color: #440;
            }

            .pln {
                color: #000;
            }

            .tag {
                color: #006;
                font-weight: bold;
            }

            .atn {
                color: #404;
            }

            .atv {
                color: #060;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                <div class="steps-wrapper xml-error-wrap">
                    <p>The Xml you uploaded contains error. Please fix these errors and upload them again.</p>
                </div>
                <div class="xml-info">
                    <ul>
                        @foreach(session('messages') as $message)
                            <li>{!! $message !!}</li>
                        @endforeach
                    </ul>
                    <div>
                     <pre class="prettyprint lang-html">
                        @foreach(session('xmlLines') as $key => $line)
                             {{--*/ $number = $key + 1; /*--}}
                             {{--*/ $messages = session('messages') /*--}}
                             {{--*/ $class = array_key_exists($number, $messages) ? 'class="error"' : '' /*--}}
                             <div id="{{ $number }}" {{$class}} style="{{ array_key_exists($number, $messages) ? 'background:#F1D3D3;': ''  }}">
                                <strong>{{ $number }} </strong>{{ $line }}
                             </div>
                         @endforeach
                     </pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('foot')
    <script type="text/javascript">
        $(window).load(function () {
            $('div > .pln:last-child').each(function () {
                if ($.trim($(this).text()) == "") {
                    $(this).remove();
                }
            });
        });
    </script>
@endsection
