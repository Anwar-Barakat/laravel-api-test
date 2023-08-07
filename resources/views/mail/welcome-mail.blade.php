<x-mail::message>
# Welcome {{ $user->name }}

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>

<x-mail::table>
| Laravel | Table | Laravel | Table |
| :------- | :----- | :------- | :----- |
| COl is  | Col is  | COl is  | Col is
</x-mail::table>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
