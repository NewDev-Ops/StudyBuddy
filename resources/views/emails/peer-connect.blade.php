<x-mail::message>
# {{ $senderName }} wants to connect with you on Revisor

**{{ $senderName }}** from **{{ $senderUniversity }}** is also strong in **{{ $subjectName }}** and would like to connect with you.

@if($note)
> "{{ $note }}"

@endif
You're receiving this because you're visible in Revisor's peer network — students who opt in to be discoverable by others studying the same subjects.

Simply reply to this email to respond directly to {{ $senderName }}.

Happy studying,<br>
The Revisor Team
</x-mail::message>
