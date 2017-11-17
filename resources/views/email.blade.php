Hi {{ $name }},<br>

Thank you for registering with Salvation 2K16. We look forward to meet you at our symposium and have fun.<br>
<br>
You have selected to participate in the following events:
<br>

<ul>
    @foreach($event as $row)
        <li>
            {{
                $item = Event::where('id', $row)->first()->e_name;
                echo $item
            }}
        </li>
    @endforeach
</ul>

With Regard,
Salvationz 2k16 Team.