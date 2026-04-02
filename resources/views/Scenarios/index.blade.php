@extends('layouts.menu')
@section('title', __('ui.scenarios.title'))

@section('styles')
    <style>
        .scenario-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 12px;
        }

        .scenario-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .scenario-section {
            border: 1px solid var(--line);
            border-radius: 12px;
            background: color-mix(in srgb, var(--surface-muted) 92%, transparent);
            padding: 10px 12px;
            margin-bottom: 10px;
        }

        .scenario-section h6 {
            margin: 0 0 8px;
            font-size: 0.88rem;
            color: var(--ink);
        }

        .scenario-section p {
            margin: 0 0 4px;
            font-size: 0.86rem;
            color: var(--ink-body);
        }

        .scenario-section p:last-child {
            margin-bottom: 0;
        }

        @media (max-width: 640px) {
            .scenario-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-shell">
        <section class="page-head">
            <div>
                <h2 class="page-title">{{ __('ui.scenarios.index_title') }}</h2>
                <p class="page-subtitle">{{ __('ui.scenarios.index_subtitle') }}</p>
            </div>
            <a href="{{ route('scenario.create') }}" class="btn btn-primary">{{ __('ui.scenarios.create_scenario') }}</a>
        </section>

        @if($scenarios->isEmpty())
            <section class="page-empty">
                <p class="mb-2">{{ __('ui.scenarios.empty') }}</p>
                <a href="{{ route('scenario.create') }}" class="btn btn-outline-primary">{{ __('ui.scenarios.add_first') }}</a>
            </section>
        @else
            <section class="scenario-grid">
                @foreach($scenarios as $scenario)
                    <article class="card scenario-card">
                        <div class="card-header d-flex justify-content-between align-items-center gap-2">
                            <strong>{{ __('ui.scenarios.scenario_number', ['id' => $scenario->id]) }}</strong>
                            <span class="chip">{{ optional($scenario->device)->name ?? __('ui.scenarios.unknown_device') }}</span>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <div class="kv-grid mb-3">
                                <div class="kv-item">
                                    <small>{{ __('ui.scenarios.key') }}</small>
                                    <strong>{{ $scenario->key }}</strong>
                                </div>
                                <div class="kv-item">
                                    <small>{{ __('ui.scenarios.value') }}</small>
                                    <strong>{{ $scenario->value }}</strong>
                                </div>
                            </div>

                            @if($scenario->scenarioLog)
                                <div class="scenario-section">
                                    <h6>{{ __('ui.scenarios.logging') }}</h6>
                                    <p><strong>{{ __('ui.scenarios.format') }}:</strong> {{ $scenario->scenarioLog->format }}</p>
                                </div>
                            @endif

                            @if($scenario->scenarioApi)
                                <div class="scenario-section">
                                    <h6>{{ __('ui.scenarios.external_api') }}</h6>
                                    <p><strong>{{ __('ui.scenarios.url') }}:</strong> {{ $scenario->scenarioApi->url }}</p>
                                    <p><strong>{{ __('ui.scenarios.payload') }}:</strong> {{ $scenario->scenarioApi->format }}</p>
                                </div>
                            @endif

                            @if($scenario->ScenarioDb)
                                <div class="scenario-section">
                                    <h6>{{ __('ui.scenarios.database') }}</h6>
                                    <p><strong>{{ __('ui.scenarios.login') }}:</strong> {{ $scenario->ScenarioDb->login }}</p>
                                    <p><strong>{{ __('ui.scenarios.database_name') }}:</strong> {{ $scenario->ScenarioDb->db_name }}</p>
                                    <p><strong>{{ __('ui.scenarios.table') }}:</strong> {{ $scenario->ScenarioDb->table_name }}</p>
                                    <p><strong>{{ __('ui.scenarios.key_value') }}:</strong> {{ $scenario->ScenarioDb->name_key }} / {{ $scenario->ScenarioDb->name_value }}</p>
                                </div>
                            @endif

                            @if($scenario->scenarioNotify)
                                <div class="scenario-section">
                                    <h6>{{ __('ui.scenarios.notification') }}</h6>
                                    <p><strong>{{ __('ui.scenarios.type') }}:</strong> {{ $scenario->scenarioNotify->type ?: __('ui.common.not_specified') }}</p>
                                    <p><strong>{{ __('ui.scenarios.text') }}:</strong> {{ $scenario->scenarioNotify->format ?: __('ui.common.not_specified') }}</p>
                                </div>
                            @endif

                            @if($scenario->scenarioModule)
                                <div class="scenario-section">
                                    <h6>{{ __('ui.scenarios.state_change') }}</h6>
                                    <p><strong>{{ __('ui.scenarios.device') }}:</strong> {{ optional($scenario->scenarioModule->device)->name ?? __('ui.scenarios.unknown_device') }}</p>
                                    <p><strong>{{ __('ui.scenarios.command') }}:</strong> {{ $scenario->scenarioModule->command }}</p>
                                    <p><strong>{{ __('ui.scenarios.argument') }}:</strong> {{ $scenario->scenarioModule->arg ?: '—' }}</p>
                                </div>
                            @endif

                            <div class="mt-auto d-flex flex-wrap gap-2 pt-2">
                                <a href="{{ route('scenario.edit', $scenario->id) }}" class="btn btn-outline-primary">{{ __('ui.scenarios.edit') }}</a>
                                <form action="{{ route('scenario.delete', $scenario->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('{{ __('ui.scenarios.delete_confirm', ['id' => $scenario->id]) }}')">{{ __('ui.scenarios.delete') }}</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>
        @endif
    </div>
@endsection
