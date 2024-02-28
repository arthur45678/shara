<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

     <title>One Page Resume</title>

     <style type="text/css">
        * { margin: 0; padding: 0; }
        body { font: 16px Helvetica, Sans-Serif; line-height: 24px; background: url(images/noise.jpg); }
        .clear { clear: both; }
        #page-wrap { width: 800px; margin: 40px auto 60px; }
        #pic { float: right; margin: -30px 0 0 0; }
        h1 { margin: 0 0 16px 0; padding: 0 0 16px 0; font-size: 42px; font-weight: bold; letter-spacing: -2px; border-bottom: 1px solid #999; }
        h2 { font-size: 20px; margin: 0 0 6px 0; position: relative; }
        h2 span { position: absolute; bottom: 0; right: 0; font-style: italic; font-family: Georgia, Serif; font-size: 16px; color: #999; font-weight: normal; }
        p { margin: 0 0 16px 0; }
        a { color: #999; text-decoration: none; border-bottom: 1px dotted #999; }
        a:hover { border-bottom-style: solid; color: black; }
        ul { margin: 0 0 32px 17px; }
        #objective { width: 500px; float: left; }
        #objective p { font-family: Georgia, Serif; font-style: italic; color: #666; }
        dt { font-style: italic; font-weight: bold; font-size: 18px; text-align: right; padding: 0 26px 0 0; width: 150px; float: left; height: 100px; border-right: 1px solid #999;  }
        dd { width: 600px; float: right; }
        dd.clear { float: none; margin: 0; height: 15px; }

        .image{
            width: 80%;
            height: 80%;
            max-width: 250px;
            max-height: 350px;
            border: 1px solid grey;
        }
     </style>
</head>

<body>

    <div id="page-wrap">
    
    
        <div id="contact-info" class="vcard"> 
        
            <!-- Microformats! -->
        @if($image && $image != '')<img src="uploads/{{$image}}" class="image" alt="Photo of Cthulu" id="pic" />@endif
        
            <h1 class="fn">{{$firstName}} {{$lastName}}</h1>

        
            <p>
                Cell: <span class="tel">{{$phoneNumber}}</span><br />
                Email: <a class="email" href="mailto:greatoldone@lovecraft.com">{{$email}}</a>
            </p>
        </div>
        
        <div class="clear"></div>
        
        <dl>
            <dd class="clear"></dd>
            
            <dt>Personal Details</dt>
            <dd>
                <p><strong>Birth Date:</strong> {{$birthDate}}<br />
                   <strong>Loaction:</strong> {{$location}}</p>

            </dd>
            
            <dd class="clear"></dd>

            <dt>Education</dt>
            <dd>
                <strong>Currently Student:</strong> {{$student}}</p>
                <ul>
                @if(count($educations)>0)
                    @foreach($educations as $education)
                    <li>{{$education}}</li>
                    @endforeach
                @endif
                </ul>
               
            </dd>

            <dd class="clear"></dd>
            
            <dt>Languages</dt>
            <dd>
                <ul>
                @if(count($languages)>0)
                 @foreach($languages as $lang)
                    <li>{{$lang->language}}</li>
                @endforeach
                @endif
                </ul>
            </dd>
            
            <dd class="clear"></dd>
            
            <dt>Skills</dt>
            <dd>
                <ul>
                @if(count($skills)>0)
                @foreach($skills as $skill)
                    <li>{{$skill->name}}</li>
                @endforeach
                @endif
                </ul>
            </dd>

            <dd class="clear"></dd>
            
            <dt>Availability</dt>
            <dd>
                <p><strong>Working Area:</strong> {{$workingArea}}<br />

                   <strong>Schedule:</strong> {{$schedule}}</p>
            </dd>
            
            <dd class="clear"></dd>

            <dd class="clear"></dd>
            
            <dt>Transport</dt>
            <dd>
                <p><strong>Transportation mean which can be used for work:</strong>
                <ul>
                @if(count($transports)>0)
                @foreach($transports as $trans)
                    <li>{{ucfirst($trans)}}</li>
                @endforeach
                @endif
                </ul>

                <strong>Driving license:</strong>{{$drivingLicense}}
                </p>
            </dd>
            
            <dd class="clear"></dd>
            
        </dl>
        <div class="clear"></div>
    
    </div>

</body>

</html>