<div id="system_management">
  <div id="service_info">
    PDSystem Service : {$service->status|default:"stopped"} 
    {if $service->status == "started"}
      <a href="{#APPLICATION_URL#}{#APPLICATION_CONTROLLER#}?section=home&function=stop_pdsystem">stop</a>
    {else}
      <a href="{#APPLICATION_URL#}{#APPLICATION_CONTROLLER#}?section=home&function=start_pdsystem">start</a>{/if}
  </div>
  <div id="service_info">
    Display Transcripts : {$service->transcripts_enabled|default:"off"} 
    {if $service->transcripts_enabled == "on"}
      <a href="{#APPLICATION_URL#}{#APPLICATION_CONTROLLER#}?section=home&function=disable_transcripts">disable</a>
    {else}
      <a href="{#APPLICATION_URL#}{#APPLICATION_CONTROLLER#}?section=home&function=enable_transcripts">enable</a>{/if}
  </div>
  <div id="service_info">
    Send Email Alerts : {$service->emails_enabled|default:"off"} 
    {if $service->emails_enabled == "on"}
      <a href="{#APPLICATION_URL#}{#APPLICATION_CONTROLLER#}?section=home&function=disable_emails">disable</a>
    {else}
      <a href="{#APPLICATION_URL#}{#APPLICATION_CONTROLLER#}?section=home&function=enable_emails">enable</a>{/if}
  </div>
</div>