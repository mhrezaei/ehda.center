@extends('front.ecg.frame.frame')

{{ null, $heartBeat = 72 }}

@section('control-panel')
    <div class="monitor-control-panel-section">
        <div class="rhy_item selected" id="R1_72_0_0">Sinus Rhythm</div>
        <div class="rhy_item" id="R2_54_1_0">Sinus Bradycardia</div>
        <div class="rhy_item" id="R3_138_2_0">Sinus Tachycardia</div>
        <div class="rhy_item" id="R4_78_3_0">Sinus Arrhythmia</div>
        <div class="rhy_item" id="R5_48_4_0">Sinus Exit Block</div>
        <div class="rhy_item" id="R6_54_5_0">Sinus Arrest</div>
    </div>
    <div class="monitor-control-panel-section">
        <div class="rhy_item" id="R7_84_6_1">NSR with PAC</div>
        <div class="rhy_item" id="R8_180_7_1">SVT</div>
        <div class="rhy_item" id="R9_90_8_1">Atrial Fibrillation</div>
        <div class="rhy_item" id="R10_75_9_1">Atrial Flutter</div>
        <div class="rhy_item" id="R28_60_10_1">Paced Atrial</div>
    </div>
    <div class="monitor-control-panel-section">
        <div class="rhy_item" id="R22_74_11_2">NSR with 1° AVB</div>
        <div class="rhy_item" id="R23_48_12_2">2° AVB Type I</div>
        <div class="rhy_item" id="R24_60_13_2">2° AVB Type II</div>
        <div class="rhy_item" id="R11_38_14_2">2° AVB 2:1</div>
        <div class="rhy_item" id="R25_36_15_2">3° AV Block</div>
    </div>
    <div class="monitor-control-panel-section">
        <div class="rhy_item" id="R12_84_16_3">NSR with PJC</div>
        <div class="rhy_item selected" id="R13_48_17_3">Junctional Rhythm</div>
        <div class="rhy_item" id="R14_82_18_3">Accel Junctional</div>
        <div class="rhy_item" id="R15_186_19_3">Junctional Tachy</div>
        <div class="rhy_item" id="R27_78_20_3">Wandering Pacemaker</div>
    </div>
    <div class="monitor-control-panel-section">
        <div class="rhy_item" id="R16_68_21_4">NSR with PVC</div>
        <div class="rhy_item" id="R17_36_22_4">Idioventricular</div>
        <div class="rhy_item" id="R18_84_23_4">Accelerated IVR</div>
        <div class="rhy_item" id="R19_210_24_4">VTach</div>
        <div class="rhy_item" id="R20_0_25_4">VFib</div>
        <div class="rhy_item" id="R26_80_26_4">Paced Ventricular</div>
    </div>
@append

@section('diagram')
    <div class="static-diagram" id="r1rhy"></div>
@append

@section('end-of-body')
    {{ Html::script('assets/libs/ecg/ecg.min.js') }}
@append

@section('extra')
    <div class="col-xs-12 mt10 text-white">
        <h2 class="rhy-title"></h2>
        <p class="rhy-description"></p>
    </div>
@append