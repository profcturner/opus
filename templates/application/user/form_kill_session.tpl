{if $session_killed}
{#session_killed#}:{$session_killed}
{/if}
<div id="functions">
  <strong>kill_session</strong>
  <form>
    session_id:<input type="text" name="session_id" />
    <input type="hidden" name="section" value="user"/>
    <input type="hidden" name="function" value="kill_session" />
    <input type="hidden" name="interactive" value="true" />
    <input class="submit" type="submit" value="kill session" />
  </form>
</div>