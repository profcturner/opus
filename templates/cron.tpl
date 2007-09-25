{$config.opus.title_short} {$config.opus.version}.{$config.opus.minor_version}.{$config.opus.patch_version}
{$content}
{if $config.opus.benchmarking}Execution Time:{$benchmark->elapsed()|string_format:"%.2f"} seconds{/if}

