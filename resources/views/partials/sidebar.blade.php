<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-bold">{{ config('app.name') }}</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Chat IA -->
                <li class="nav-item">
                    <a href="{{ route('ai.chat') }}"
                       class="nav-link {{ request()->routeIs('ai.chat') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-robot"></i>
                        <p>Chat IA</p>
                    </a>
                </li>

                <!-- Análisis IA de Código -->
                <li class="nav-item">
                    <a href="{{ route('code_analysis.index') }}"
                       class="nav-link {{ request()->routeIs('code_analysis.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Análisis de Código IA</p>
                    </a>
                </li>

                <!-- Recursos Sub‑menu -->
                <li class="nav-item has-treeview {{ request()->routeIs('graficos') || request()->routeIs('normas') || request()->routeIs('apoyo') ? 'menu-open' : '' }}">
                    <a href="#"
                       class="nav-link {{ request()->routeIs('graficos') || request()->routeIs('normas') || request()->routeIs('apoyo') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Recursos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('graficos.index') }}"
                               class="nav-link {{ request()->routeIs('graficos') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gráficos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('normas') }}"
                               class="nav-link {{ request()->routeIs('normas') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Normas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('apoyo') }}"
                               class="nav-link {{ request()->routeIs('apoyo') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Apoyo</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('projects.documents.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Proyecto Analizado</p>
                    </a>
                </li>

                <!-- Proyectos -->
                <li class="nav-item">
                    <a href="{{ route('projects.index') }}"
                       class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>Proyectos</p>
                    </a>
                </li>

                <!-- Documentos del proyecto Sub‑menu -->
                @if(isset($project))
                <li class="nav-item has-treeview {{ request()->routeIs('projects.documents.show') || request()->routeIs('projects.documents.update') ? 'menu-open' : '' }}">
                    <a href="#"
                       class="nav-link {{ request()->routeIs('projects.documents.show') || request()->routeIs('projects.documents.update') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Documentos del proyecto
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @php
                            $sections = [
                                'problema'         => '1. Problema de investigación',
                                'objetivos'        => '2. Objetivos',
                                'justificacion'    => '3. Justificación',
                                'estado_arte'      => '4. Estado del arte',
                                'marco_teorico'    => '5.1 Marco teórico',
                                'marco_geografico' => '5.2 Marco geográfico',
                                'marco_normativo'  => '5.3 Marco normativo / legal',
                                'metodologia'      => '6. Metodología',
                                'analisis'         => '7. Análisis de resultados y discusión',
                                'conclusiones'     => '8. Conclusiones',
                                'recomendaciones'  => '9. Recomendaciones',
                                //'portada'          => '10. Portada',
                            ];
                        @endphp
                        @foreach($sections as $key => $label)
                            <li class="nav-item">
                                <a href="{{ route('projects.documents.show', [$project, $key]) }}"
                                   class="nav-link {{ request()->routeIs('projects.documents.show') && request()->segment(4)==$key ? 'active' : '' }}">
                                    <i class="far fa-file nav-icon"></i>
                                    <p>{{ $label }}</p>
                                </a>
                            </li>
                        @endforeach
                        <li class="nav-item">
                            <a href="{{ route('projects.cover.edit', $project->id) }}"
                            class="nav-link {{ request()->routeIs('projects.cover.edit') ? 'active' : '' }}">
                                <i class="fas fa-file nav-icon"></i>10. Portada proyecto
                            </a>
                        </li>
                    </ul>
                </li>
                
                @endif

            </ul>
        </nav>
    </div>
</aside>
