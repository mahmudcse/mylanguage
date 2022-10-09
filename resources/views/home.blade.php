@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card" style="margin-top: 60px;">

                    <div class="alert alert-success d-none" role="alert" id="successMessege">
                        Success
                    </div>

                    <div class="alert alert-danger d-none" role="alert" id="errorMessege">
                        Not saved!
                    </div>

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
                                <span style="cursor: pointer" onclick="clearField('searchWord')"><img src="/images/x-lg.svg"
                                        alt="clear"></span>

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




<!-- Modal -->
<div class="modal fade" id="wordModal" tabindex="-2" aria-labelledby="wordModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWordLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <input type="text" id="word" class="form-control modalForm mb-3" placeholder="word">
                    <span style="cursor: pointer" onclick="clearField('word')"><img src="/images/x-lg.svg"
                            alt="clear"></span>
                </div>
                <div class="input-group">
                    <input type="text" id="definition" class="form-control modalForm mb-3" placeholder="definition">
                    <span style="cursor: pointer" onclick="clearField('definition')"><img src="/images/x-lg.svg"
                            alt="clear"></span>
                </div>
                <div class="input-group">
                    <select id="articles" name="articles" class="form-select" aria-label="">

                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="hideModal('wordModal')">
                    <img src="/images/x-lg-white.svg" alt="close icon">
                </button>

                <button type="submit" id="saveData" class="btn btn-secondary" onclick="">
                    <img src="/images/save.svg" alt="save icon">
                </button>
            </div>
        </div>
    </div>
</div>


{{-- Article modal --}}


<div class="modal fade" id="articleModal" tabindex="-2" aria-labelledby="articleModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="articleModalLabel">Add reference article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <input type="text" id="article_title" class="form-control articleModalForm mb-3"
                        placeholder="Title">
                    <span style="cursor: pointer" onclick="clearField('article_title')"><img src="/images/x-lg.svg"
                            alt="clear"></span>
                </div>
                <div class="input-group">
                    <input type="text" id="article_link" class="form-control articleModalForm"
                        placeholder="Article reference">
                    <span style="cursor: pointer" onclick="clearField('article_link')"><img src="/images/x-lg.svg"
                            alt="clear"></span>
                </div>
            </div>

            {{-- checkArticleValidity --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="hideModal('articleModal')">
                    <img src="/images/x-lg-white.svg" alt="close icon">
                </button>

                <button type="submit" id="saveArticle" class="btn btn-secondary" onclick="">
                    <img src="/images/save.svg" alt="save icon">
                </button>
            </div>
        </div>
    </div>
</div>


{{-- are you sure box --}}

<div class="modal fade" id="confirm" tabindex="-2" aria-labelledby="wordModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                Are you sure to delete this word?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="actionYes" onclick="">Delete</button>

                <button type="button" class="btn btn-secondary" id="actionNo" onclick="cancelAction()">Cancel</button>
            </div>
        </div>
    </div>
</div>
