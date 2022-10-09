@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card" style="margin-top: 60px;">
                    <div class="card-header">
                        {{-- {{ __('Dashboard') }} --}}
                        <button class="btn btn-default" onclick="viewArticles()">Links

                        </button>

                        <button onclick="viewAll()" id="viewAll" class="btn btn-secondary" data-toggle="tooltip"
                            data-placement="top" title="view">
                            <img src="/images/eye.svg" alt="trash icon" width="25" height="25">
                        </button>

                        <button onclick="loadWords('history')" id="history" class="btn btn-secondary" data-toggle="tooltip"
                            data-placement="top" title="History">
                            <img src="/images/clock-history.svg" alt="history icon" width="25" height="25">
                        </button>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-6">
                                <button id="addArticle" onclick="addArticle()" class="btn btn-secondary"
                                    data-toggle="tooltip" data-placement="top" title="Add article">

                                    <img src="/images/link.svg" alt="article icon" width="25" height="25">
                                </button>

                                <button id="addButton" onclick="addWord()" class="btn btn-secondary" data-toggle="tooltip"
                                    data-placement="top" title="add">
                                    <img src="/images/plus.svg" alt="trash icon" width="25" height="25">
                                </button>

                            </div>
                            <div class="col-6 d-flex">

                                <input type="text" onkeyup="searchWord(event)" name="searchWord" id="searchWord"
                                    class="form-control" placeholder="Search for words">
                                <span style="cursor: pointer" onclick="clearField('searchWord')"><img
                                        src="/images/x-lg-white.svg" alt="clear"></span>

                            </div>

                        </div>
                        <div class="row">
                            <div class="col-6 d-flex">
                                <input onchange="dateSearch()" type="text" name="startDate" id="startDate"
                                    class="form-control datepicker" placeholder="Start date">

                                <img onclick="clearField('startDate')" style="cursor: pointer" height="20" width="20"
                                    src="/images/x-lg.svg" alt="clear">

                            </div>
                            <div class="col-6 d-flex">

                                <input onchange="dateSearch()" type="text" name="endDate" id="endDate"
                                    class="form-control datepicker" placeholder="End date">

                                <img onclick="clearField('endDate')" style="cursor: pointer" height="20" width="20"
                                    src="/images/x-lg.svg" alt="clear">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 d-flex">
                                <select id="wordsOnArticle" onchange="loadWordsOnArticle()" class="form-select"
                                    aria-label="Default select example">

                                </select>

                                

                            </div>
                            <div class="col-5 d-flex">
                                <select id="no_of_read" onchange="loadWordsOnRead()" class="form-select"
                                    aria-label="Default select example">

                                </select>

                            </div>
                        </div>

                        <br><br>



                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        <input type="hidden" name="checkAll" id="checkAll" onchange="checkAll()">
                                        <span>Word</span>
                                    </th>
                                    <th scope="col">Definition</th>
                                    <th scope="col">Learned</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>

                            <tbody id="wordsTable">
                                <tr id="skeleton1">
                                    <td>Loading...</td>
                                    <td>Loading...</td>
                                    <td>Loading...</td>
                                    <td>Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



