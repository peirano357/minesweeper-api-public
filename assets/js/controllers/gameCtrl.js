'use strict';
/* Controllers */

app.controller('GameMainCtrl', ['$scope', 'accountFactory', 'gameSessionFactory', 'SweetAlert', 'toaster', '$uibModal', '$stateParams', '$state',
    function ($scope, accountFactory, gameSessionFactory, SweetAlert, toaster, $uibModal, $stateParams, $state) {

        function init() {
            $scope.gameSize = 0;
            $scope.gameNotCteated = true;
            $scope.isLoading = true;
            $scope.board = {rows: [], columns: []};
            $scope.matrix = [];
            $scope.gameOver = false;
            $scope.currentSesionId = 0;

            var token = getCurrentToken();
            if (typeof token === 'undefined')
            {
                destroyLoginCookie();
                window.location.href = "/";
            }


            // BEGIN - CHECK IF IS LOADING GAME
            if (typeof $stateParams.id !== 'undefined' && !isNaN($stateParams.id)) {
                // load
                $scope.isLoading = true;
                $scope.currentSesionId = $stateParams.id;

                gameSessionFactory.getGameSessionDetail($stateParams.id, token).error(function (response) {

                    if (response.code == '404') {
                        // Game session not created
                        SweetAlert.swal('Something went wrong...', 'Service not found', "error");
                    } else {
                        SweetAlert.swal('Something went wrong...', response.message, "error");
                    }

                    $scope.isLoading = false;

                }).success(function (response) {
                    $scope.gameNotCteated = false;
                    $scope.isLoading = false;
                   // toaster.pop('success', 'Game session', 'Your game session has been loaded!.');
                    $scope.gameSize = response.data.size;
                    $scope.matrix = JSON.parse(response.data.content);
                });

            } else
            {
                // new game
                $scope.isLoading = false;
            }
            // END - CHECK IF IS LOADING GAME

        }

        $scope.createGame = function () {

            var modalInstance = $uibModal.open({
                templateUrl: '/assets/views/modals/newGame.html',
                controller: 'NewGameModalCtrl',
                // size: 'sm',
                resolve: {
                    items: function () {
                        return $scope.items;
                    }
                }
            });
            modalInstance.result.then(function (game) {
                init();
                $scope.gameNotCteated = false;
                generateBoard(game);
                $scope.gameSize = game.size;


            }, function () {
                // alert('Modal dismissed at: ' + new Date());
            });
        };
        /* Begin UI functions */


        function showAllMines() {
            for (var i = 0; i < $scope.matrix.length; i++) {
                $scope.matrix[i].status = 'opened';
            }
        }

        function checkNextToCell(cell, index) {
            if (cell.content == 'm') {
                return 1;
            }
            return 0;
        }

        function generateNearbyMinesValue(currentRow, currentCol, matrix, cell) {
            cell.status = 'opened';
            var currentValue = 0;
            var adjacent1, adjacent2, adjacent3, adjacent4, adjacent5, adjacent6, adjacent7, adjacent8 = {};
            for (var i = 0; i < matrix.length; i++) {

                if (currentCol - 1 >= 0 && matrix[i].col == currentCol - 1) {
                    //  CHECK PREVIOUS COLUMN (if)

                    // row -1
                    if (currentRow - 1 >= 0 && matrix[i].row == currentRow - 1) {

                        if (matrix[i].content == 'm') {
                            currentValue++;
                        }
                        adjacent1 = matrix[i];
                    }

                    // row
                    if (matrix[i].row == currentRow) {

                        if (matrix[i].content == 'm') {
                            currentValue++;
                        }
                        adjacent8 = matrix[i];
                    }

                    // row +1
                    if (currentRow + 1 < $scope.gameSize && matrix[i].row == currentRow + 1) {

                        if (matrix[i].content == 'm') {
                            currentValue++;
                        }
                        adjacent7 = matrix[i];
                    }
                }


                if (currentCol >= 0 && matrix[i].col == currentCol) {
                    //  CHECK CURRENT COLUMN 
                    //  
                    // row -1
                    if (currentRow - 1 >= 0 && matrix[i].row == currentRow - 1) {
                        if (matrix[i].content == 'm') {
                            currentValue++;
                        }
                        adjacent2 = matrix[i];
                    }
                    // row +1
                    if (currentRow + 1 < $scope.gameSize && matrix[i].row == currentRow + 1) {
                        if (matrix[i].content == 'm') {
                            currentValue++;
                        }
                        adjacent6 = matrix[i];
                    }
                }


                if (currentCol + 1 < $scope.gameSize && matrix[i].col == currentCol + 1) {
                    //  CHECK NEXT COLUMN (if)
                    // row -1
                    if (currentRow - 1 >= 0 && matrix[i].row == currentRow - 1) {
                        if (matrix[i].content == 'm') {
                            currentValue++;
                        }
                        adjacent3 = matrix[i];
                    }

                    // row
                    if (matrix[i].row == currentRow) {
                        if (matrix[i].content == 'm') {
                            currentValue++;
                        }
                        adjacent4 = matrix[i];
                    }

                    // row +1
                    if (currentRow + 1 < $scope.gameSize && matrix[i].row == currentRow + 1) {
                        if (matrix[i].content == 'm') {
                            currentValue++;
                        }
                        adjacent5 = matrix[i];
                    }
                }
            }

            cell.value = currentValue;
            if (cell.value === 0) {
                // call recursively this function to clean all nearby cells
                recursiveCallForAllNearby(matrix, adjacent1, adjacent2, adjacent3, adjacent4, adjacent5, adjacent6, adjacent7, adjacent8);

            }

        }

        function recursiveCallForAllNearby(matrix, adjacent1, adjacent2, adjacent3, adjacent4, adjacent5, adjacent6, adjacent7, adjacent8) {
            if (typeof adjacent1 !== 'undefined') {

                if (adjacent1.status == 'hidden') {
                    generateNearbyMinesValue(adjacent1.row, adjacent1.col, matrix, adjacent1);
                }

            }
            if (typeof adjacent2 !== 'undefined') {
                if (adjacent2.status == 'hidden') {
                    generateNearbyMinesValue(adjacent2.row, adjacent2.col, matrix, adjacent2);
                }
            }

            if (typeof adjacent3 !== 'undefined') {
                if (adjacent3.status == 'hidden') {
                    generateNearbyMinesValue(adjacent3.row, adjacent3.col, matrix, adjacent3);
                }
            }

            if (typeof adjacent4 !== 'undefined') {
                if (adjacent4.status == 'hidden') {
                    generateNearbyMinesValue(adjacent4.row, adjacent4.col, matrix, adjacent4);
                }
            }

            if (typeof adjacent5 !== 'undefined') {
                if (adjacent5.status == 'hidden') {
                    generateNearbyMinesValue(adjacent5.row, adjacent5.col, matrix, adjacent5);
                }
            }

            if (typeof adjacent6 !== 'undefined') {
                if (adjacent6.status == 'hidden') {
                    generateNearbyMinesValue(adjacent6.row, adjacent6.col, matrix, adjacent6);
                }
            }
            if (typeof adjacent7 !== 'undefined') {
                if (adjacent7.status == 'hidden') {
                    generateNearbyMinesValue(adjacent7.row, adjacent7.col, matrix, adjacent7);
                }
            }

            if (typeof adjacent8 !== 'undefined') {
                if (adjacent8.status == 'hidden') {
                    generateNearbyMinesValue(adjacent8.row, adjacent8.col, matrix, adjacent8);
                }
            }


        }

        function drawBoardUI(game) {
            for (var i = 0; i < game.size; i++) {
                for (var j = 0; j < game.size; j++) {
                    $scope.matrix.push({row: i, col: j, content: '', status: 'hidden'});
                }
            }

            var mines = plantMines($scope.matrix, game.mines);
            $scope.mines = mines;
            for (var i = 0; i < mines.length; i++) {
                for (var j = 0; j < $scope.matrix.length; j++) {
                    if ($scope.matrix[j].col === mines[i].col && $scope.matrix[j].row === mines[i].row) {
                        $scope.matrix[j].content = 'm';
                    }
                }
            }
        }


        /**
         * The function below generates n mines in random positions
         * @param {array} arr
         * @param {int} n
         * @returns {Array}
         */
        function plantMines(arr, n) {
            var result = new Array(n),
                    len = arr.length,
                    taken = new Array(len);
            if (n > len)
                throw new RangeError("getRandom: more elements taken than available");
            while (n--) {
                var x = Math.floor(Math.random() * len);
                result[n] = arr[x in taken ? taken[x] : x];
                taken[x] = --len;
            }
            return result;
        }


        function generateBoard(game) {
            drawBoardUI(game);
        }

        /**
         * Checks if game is Over (win) or still have cells to be revealed.
         * @returns {boolean}
         */
        function checkGameOverWin() {

            for (var i = 0; i < $scope.matrix.length; i++) {
                if ($scope.matrix[i].status == 'hidden' && $scope.matrix[i].content != 'm') {
                    return false;
                }
            }

            return true;
        }

        /**
         * This function is called from UI when clicking a non-revealed cell
         * @param {object} cell
         */
        $scope.showCellContent = function (cell) {
            cell.status = 'opened';
            if (cell.content == 'm') {
                $scope.gameOver = true;
                SweetAlert.swal("Ka-boom!", "You landed on a mine... Try again.", "error");
                showAllMines();
            } else {
                // Check is game is over (WIN)
                if (checkGameOverWin() === true) {
                    $scope.gameOver = true;
                    SweetAlert.swal("Ye-haaa!", "You won the game... Try again in higher difficulty.", "success");
                    showAllMines();

                } else {
                    // SHOW NUMBER OF MINES NEARBY
                    generateNearbyMinesValue(cell.row, cell.col, $scope.matrix, cell);
                }

            }
        }

        $scope.flag = function (cell) {

            if (cell.flaged !== true) {
                cell.flaged = true;
            } else {
                cell.flaged = false;
            }
        }
        // CALL THE INIT FUNCTION FIRST
        init();


        /* BEGIN - API CALLS FUNCTIONS */
        $scope.createGameSession = function () {

            var object = {};
            var token = getCurrentToken();
            object.token = token;
            object.content = JSON.stringify($scope.matrix);
            object.size = $scope.gameSize;
            $scope.isLoading = true;

            gameSessionFactory.CreateGameSession(token, object).error(function (response) {
                $scope.isLoading = true;
                if (response.code == '404') {
                    // Game session not created
                    SweetAlert.swal('Something went wrong...', 'Service not found', "error");
                } else {
                    SweetAlert.swal('Something went wrong...', response.message, "error");
                }
                $scope.gameNotCteated = false;
                $scope.isLoading = false;

            }).success(function (response) {
                $state.go('app.ui.gamedetail', {id: response.data});
                $scope.gameNotCteated = false;
                $scope.isLoading = false;
                toaster.pop('success', 'Game session', 'Your game session has been created!.');
                $scope.currentSesionId = response.data;
            });
        }


        $scope.updateGameSession = function () {

            var object = {};
            var token = getCurrentToken();
            object.token = token;
            object.content = JSON.stringify($scope.matrix);
            object.size = $scope.gameSize;
            object.id = $scope.currentSesionId;
            $scope.isLoading = true;

            gameSessionFactory.UpdateGameSession(token, object).error(function (response) {
                $scope.isLoading = true;
                if (response.code == '404') {
                    // Game session not created
                    SweetAlert.swal('Something went wrong...', 'Service not found', "error");
                } else {
                    SweetAlert.swal('Something went wrong...', response.message, "error");
                }
                $scope.gameNotCteated = false;
                $scope.isLoading = false;

            }).success(function (response) {
                $scope.gameNotCteated = false;
                $scope.isLoading = false;
                toaster.pop('success', 'Game session', 'Current game session has been updated!.');
                $scope.currentSesionId = response.data;
            });
        }


        /* END - API CALLS FUNCTIONS */

    }]);

/**
 * Controller for the NEW GAME modal screen
 */
app.controller('NewGameModalCtrl', ["$scope", "$uibModalInstance", "items", function ($scope, $uibModalInstance, items) {

        $scope.game = {};
        $scope.game.size = 20;
        $scope.game.mines = 30;
        $scope.ok = function () {
            $uibModalInstance.close($scope.game); // $scope.selected.item
        };
        $scope.cancel = function () {
            $uibModalInstance.dismiss('cancel');
        };
    }]);


