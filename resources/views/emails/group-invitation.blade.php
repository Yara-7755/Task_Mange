<h2>You've been invited to join {{ $invitation->group->name }}!</h2>
<p>Hello,</p>
<p>You have been invited to join the group <strong>{{ $invitation->group->name }}</strong> on Task Manager.</p>
<p>Click the link below to respond to this invitation:</p>
<a href="{{ route('invitations.show', $invitation->token) }}" style="background-color: #15803d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">Accept Invitation</a>
<p style="margin-top: 15px;">If you don't have an account, please register with this email first.</p>
